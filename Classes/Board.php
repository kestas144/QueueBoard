<?php

namespace QueueBoard\Classes;

use QueueBoard\Database\Customer;
use QueueBoard\Database\Database;
use QueueBoard\Database\QueryBuilder;

class Board
{
    private $customerId;
    private $database;
    private $employeeId;
    private $queryBuilder;
    private $employeeCustomerCount;
    private $employeeAverageTime;

    public function __construct(
        Database $database,
        QueryBuilder $queryBuilder,
        string $customerId = null,
        string $employeeId = null
    )
    {
        $this->database = $database;
        $this->queryBuilder = $queryBuilder;
        $this->customerId = $customerId;
        $this->employeeId = $employeeId;
    }

    public function setEmployeeId(): void
    {
        $this->setEmployee('all', 'employees', 'status', 'free');

        if ($this->employeeId == null) {
            $board = $this->queryBuilder
                ->select()
                ->column('employee_id')
                ->from('board')
                ->where('status', 'waiting')
                ->or('status', 'serviced');
            $data = $this->database->getData($board);
            $employees = array_count_values(array_column($data, 'employee_id'));
            asort($employees);
            reset($employees);
            $this->employeeId = key($employees);

            $this->setEmployee('all', 'employees', 'id', $this->employeeId);
        }
    }

    public function setEmployee(string $columnName, string $table, string $whereName, string $whereValue): void
    {
        $data = $this->getDataFromDb($columnName, $table, $whereName, $whereValue);
        if (!empty($data)) {
            $this->employeeId = $data[0]['id'];
            $this->employeeAverageTime = $data[0]['average_operation_time'];
            $this->employeeCustomerCount = $data[0]['customer_count'];
        }
    }

    public function getAllWaitingCustomers()
    {
        $query = $this->queryBuilder
            ->select()
            ->column('all')
            ->from('board')
            ->where('status', 'waiting')
            ->or('status', 'serviced');
        return $this->database->getData($query);
    }

    public function changeStatusAll()
    {

        $query = $this->queryBuilder
            ->select()
            ->column('id')
            ->from('employees')
            ->where('status', 'busy');
        $employees = $this->database->getData($query);
        $employeesArray = array_values(array_column($employees, 'id'));

        foreach ($employeesArray as $value) {
            $query = $this->queryBuilder
                ->select()
                ->column('id, created_at')
                ->from('board')
                ->where('employee_id', $value)
                ->and('status', 'waiting');
            $data = $this->database->getData($query);
            $countTime = null;
            $id = null;
            foreach ($data as $date) {
                $timeNow = strtotime("now");
                $timeCreated = strtotime($date['created_at']);
                $time = $timeNow - $timeCreated;
                if ($time > $countTime) {
                    $countTime = $time;
                    $id = $date['id'];
                }
            }
            $this->changeStatus($id, 'serviced', 'board');
        }
    }

    public function getEntryByCustomerId($customerId)
    {
        $query = $this->queryBuilder
            ->select()
            ->column('all')
            ->from('board')
            ->where('customer_id', $customerId)
            ->and('status', 'waiting')
            ->or('status', 'serviced')
            ->and('customer_id', $customerId);
        return $this->database->getData($query);
    }

    public function changeStatus(string $id, string $status, string $table)
    {
        $statusString = "status = '$status'";
        $this->changeColumn($statusString, $table, 'id', $id);
    }

    public function getEmployeeWaitingCustomers(string $boardId = null)
    {
        if (isset ($boardId)) {
            $this->changeStatus($boardId, 'completed', 'board');
            $operationTime = $this->getOperationTime($boardId);
            $data = $this->getDataFromDb(
                'customer_count, average_operation_time',
                'employees',
                'id',
                $this->employeeId);

            $averageTime = $this->getAverageOperationTime(
                $data[0]['customer_count'],
                $data[0]['average_operation_time'],
                $operationTime
            );
            $customerCount = $data[0]['customer_count'] + 1;
            $paramString = "customer_count = '" . $customerCount . "', average_operation_time = '$averageTime" . "'";

            $this->changeColumn($paramString, 'employees', 'id', $this->employeeId);
        }

        return $this->getDataFromDb('all', 'board', 'employee_id', $this->employeeId);
    }

    public function writeCustomerToBoard()
    {
        $this->setEmployeeId();
        $paramNameString = 'customer_id , employee_id , countdown';
        $time = $this->calculateTime();
        $paramValueString = "'$this->customerId', '$this->employeeId', '$time'";

        $boardEntryQuery = $this->queryBuilder->insertInto('board', $paramNameString, $paramValueString);
        $this->database->setData($boardEntryQuery);

        $this->changeStatus($this->employeeId, 'busy', 'employees');

    }

    public function postponeAppointment(string $employeeId, string $customerId): void
    {

        $query = $this->queryBuilder
            ->select()
            ->column('all')
            ->from('board')
            ->where('employee_id', $employeeId)
            ->orderBy('created_at', 'ASC');
        $data = $this->database->getData($query);
        var_dump($data);

    }

    private function changeColumn(string $paramString, string $table, string $whereName, string $whereValue)
    {
        $query = $this->queryBuilder
            ->update($table, $paramString)
            ->where($whereName, $whereValue);
        $this->database->setData($query);
    }

    private function getOperationTime(string $id): int
    {
        $data = $this->getDataFromDb('created_at, updated_at', 'board', 'id', $id);
        $time = strtotime($data[0]['updated_at']) - strtotime($data[0]['created_at']);
        return $time;
    }

    private function getAverageOperationTime(int $count, int $averageTimeInDb, int $averageTimeComputed): int
    {
        return ($averageTimeInDb + $averageTimeComputed) / ($count + 1);
    }

    private function getDataFromDb(string $column, string $table, string $whereField, string $whereValue)
    {
        $board = $this->queryBuilder
            ->select()
            ->column($column)
            ->from($table)
            ->where($whereField, $whereValue);
        return $this->database->getData($board);
    }

    private function calculateTime()
    {
        return $this->employeeCustomerCount * $this->employeeAverageTime;
    }

    private function camelCaseConverter(string $string): string
    {
        $str = substr($string, 1);
        $output = strtolower(preg_replace('/[A-Z]/', '_$0', $str));
        return $output;
    }

}