<?php

namespace QueueBoard\Classes;

use QueueBoard\Database\Customer;
use QueueBoard\Database\Database;
use QueueBoard\Database\QueryBuilder;

class Board
{
    private $customer;
    private $database;
    private $employeeId;
    private $queryBuilder;
    private $employeeCustomerCount;
    private $employeeAverageTime;

    public function __construct(Database $database, QueryBuilder $queryBuilder, Customer $customer = null, string $employeeId = null)
    {
        $this->database = $database;
        $this->queryBuilder = $queryBuilder;
        $this->customer = $customer;
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
        $data = $this->getDataFromDb($columnName,$table,$whereName,$whereValue);
        if (!empty($data)) {
            $this->employeeId = $data[0]['id'];
            $this->employeeAverageTime = $data[0]['average_operation_time'];
            $this->employeeCustomerCount = $data[0]['customer_count'];
        }
    }

    public function getEmployeeId()
    {
        return $this->employeeId;
    }

    public function getAllWaitingCustomers()
    {
        $query = $this->queryBuilder
            ->select()
            ->column('all')
            ->from('board')
            ->where('status', 'waiting')
            ->or('status','serviced');
        return $this->database->getData($query);
    }

    public function getEntryByCustomerId($customerId)
    {
        return $this->getDataFromDb('all', 'board', 'customer_id', $customerId);
    }

    public function getEmployeeWaitingCustomers(string $boardId = null)
    {
        if (isset ($boardId)) {
            $statusString = "status = 'completed'";
            $this->changeColumn($statusString, 'board', 'id',$boardId);
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

            $this->changeColumn($paramString, 'employees','id', $this->employeeId);
        }

        return $this->getDataFromDb('all','board','employee_id',$this->employeeId);
    }

    public function writeCustomerToBoard()
    {
        $this->setEmployeeId();
        $customerName = $this->customer->getName();

        $query = $this->queryBuilder->insertInto(
            'customers',
            'name',
            "'$customerName'"
        );
        $this->database->setData($query);

        $customerIdQuery = $this->queryBuilder
            ->select()
            ->column('id')
            ->from('customers')
            ->where('name', $customerName);
        $idArray = $this->database->getData($customerIdQuery);
        $Id = array_count_values(array_column($idArray, 'id'));
        $idValue = key($Id);
        $paramNameString = 'customer_id , employee_id , countdown';
        $time = $this->calculateTime();
        $paramValueString = "'$idValue', '$this->employeeId', '$time'";
        $boardEntryQuery = $this->queryBuilder->insertInto('board', $paramNameString, $paramValueString);
        $this->database->setData($boardEntryQuery);

        $paramString = "status = 'busy'";
        $this->changeColumn($paramString, 'employees', 'id', $this->employeeId);

    }
    public function postponeAppointment($string)
    {
var_dump($string);
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
        $data = $this->getDataFromDb('created_at, updated_at', 'board','id',$id);
        $time = strtotime($data[0]['updated_at']) - strtotime($data[0]['created_at']);
        return $time;
    }

    private function getAverageOperationTime(int $count, int $averageTimeInDb, int $averageTimeComputed): int
    {
        return ($averageTimeInDb + $averageTimeComputed) / ($count + 1);
    }

    private function getDataFromDb(string $column,  string $table, string $whereField, string $whereValue)
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