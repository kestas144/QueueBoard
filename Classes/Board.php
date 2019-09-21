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

    public function __construct(Database $database, QueryBuilder $queryBuilder, Customer $customer = null, string $employeeId = null)
    {
        $this->database = $database;
        $this->queryBuilder = $queryBuilder;
        $this->customer = $customer;
        $this->employeeId = $employeeId;
    }

    public function setEmployeeId()
    {
        $query = $this->queryBuilder->selectId()
            ->from('employees')
            ->whereStatus('free');

        $freeEmployeeId = $this->database->getData($query);

        if (empty($freeEmployeeId)) {
            $board = $this->queryBuilder->select()
                ->from('board');
            $data = $this->database->getData($board);
            $employees = array_count_values(array_column($data, 'employee_id'));
            asort($employees);
            reset($employees);
            $this->employeeId = key($employees);

        } else {
            $this->employeeId = $freeEmployeeId[0]['id'];
        }
    }

    public function getEmployeeId()
    {
        return $this->employeeId;
    }

    public function getAllWaitingCustomers()
    {
        $query = $this->queryBuilder->select()
            ->from('board');
        return $this->database->getData($query);
    }

    public function getEmployeeWaitingCustomers(string $boardId = null)
    {
        if (isset ($boardId)) {
            $this->changeStatus('completed', 'board', $boardId);
            $operationTime = $this->getOperationTime($boardId);
            $data = $this->getDataFromDb($this->employeeId, 'customer_count, average_operation_time', 'employees');
            $averageTime = $this->getAverageOperationTime(
                $data[0]['customer_count'],
                $data[0]['average_operation_time'],
                $operationTime
            );
            $customerCount = $data[0]['customer_count']+1;
            $paramString = "customer_count = '" . $customerCount . "', average_operation_time = '$averageTime"."'";

            $this->changeColumn($paramString,'employees',$this->employeeId);

        }

        $query = $this->queryBuilder->select()
            ->from('board')
            ->whereEmployeeId($this->employeeId);

        return $this->database->getData($query);
    }

    public function writeCustomerToBoard()
    {
        $customerName = $this->customer->getName();
        $query = $this->queryBuilder->insertInto(
            'customers',
            'name',
            "'$customerName'"
        );
        $this->database->setData($query);
        $customerIdQuery = $this->queryBuilder->selectId()->from('customers')->whereName($customerName);
        $customerId = $this->database->getData($customerIdQuery);

        $test = $customerId[0]['id'];
        $paramNameString = 'customer_id , employee_id';
        $paramValueString = "'$test', '$this->employeeId'";

        $boardEntryQuery = $this->queryBuilder->insertInto('board', $paramNameString, $paramValueString);
        $this->database->setData($boardEntryQuery);

        $this->changeStatus('busy', 'employees', $this->employeeId);
    }

    private function changeStatus(string $status, string $table, string $id)
    {
        $paramString = "status = '$status'";
        $query = $this->queryBuilder->update($table, $paramString)->whereId($id);
        $this->database->setData($query);
    }

    private function changeColumn(string $paramString, string $table, string $id)
    {
        $paramString;
        $query = $this->queryBuilder->update($table, $paramString)->whereId($id);
        var_dump($query);
        $this->database->setData($query);
    }

    private function getOperationTime(string $id): int
    {
        $data = $this->getDataFromDb($id, 'created_at, updated_at', 'board');
        $time = strtotime($data[0]['updated_at']) - strtotime($data[0]['created_at']);
        return $time;
    }

    private function getAverageOperationTime(int $count, int $averageTimeInDb, int $averageTimeComputed): int
    {
        return ($averageTimeInDb + $averageTimeComputed) / ($count + 1);
    }

    private function getDataFromDb(string $id, string $column, string $table)
    {
        $board = $this->queryBuilder->selectTest()
            ->column($column)
            ->from($table)
            ->whereId($id);
        return $this->database->getData($board);

    }

}