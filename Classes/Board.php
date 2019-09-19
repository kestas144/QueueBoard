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

    public function __construct(Database $database, QueryBuilder $queryBuilder, Customer $customer = null)
    {
        $this->customer = $customer;
        $this->database = $database;
        $this->queryBuilder = $queryBuilder;
    }

    public function setEmployeeId()
    {
        $query = $this->queryBuilder->selectId()
            ->from('employees')
            ->whereStatus('free');

        $freeCustomerId = $this->database->getData($query);

        if (empty($freeCustomerId)) {
            $board = $this->queryBuilder->select()
                ->from('board');
            $data = $this->database->getData($board);
            $employees = array_count_values(array_column($data, 'employee_id'));
            asort($employees);
            reset($employees);
            $this->employeeId = key($employees);

        } else {
            $this->employeeId = $freeCustomerId[0]['id'];
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
        $this->changeStatus('busy','employees',$this->employeeId);
    }

    private function changeStatus(string $status, string $table, string $id)
    {
        $paramString = "status = '$status'";
        $employeeStatus = $this->queryBuilder->update($table, $paramString)->whereId($id);
        $this->database->setData($employeeStatus);
    }



}