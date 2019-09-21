<?php

namespace QueueBoard\Database;
class QueryBuilder
{
    private $queryString = null;

    public function select(): QueryBuilder
    {
        $this->queryString = "SELECT *";
        return $this;
    }

    public function selectTest(): QueryBuilder
    {
        $this->queryString = "SELECT";
        return $this;
    }

    public function timeDiff(): QueryBuilder
    {
        $this->queryString = $this->queryString . " TIMESTAMPDIFF as timediff (MINUTE,'created_at','updated_at')";
        return $this;
    }

    public function column(string $string): QueryBuilder
    {
        if (isset($string)) {

           $this->queryString = $this->queryString . " $string";
        } else {
            $this->queryString = $this->queryString . " *";
        }
        return $this;
    }

    public function selectId(): QueryBuilder
    {
        $this->queryString = "SELECT id";
        return $this;
    }

    public function from(string $table): QueryBuilder
    {
        $this->queryString = $this->queryString . " FROM $table";
        return $this;
    }

    public function whereId($id): QueryBuilder
    {
        $this->queryString = $this->queryString . " WHERE id = '$id' ";
        return $this;
    }

    public function whereEmployeeId($id): QueryBuilder
    {
        $this->queryString = $this->queryString . " WHERE employee_id = '$id' ";
        return $this;
    }

    public function whereStatus($status): QueryBuilder
    {
        $this->queryString = $this->queryString . " WHERE status = '$status' ";
        return $this;
    }

    public function whereName($name): QueryBuilder
    {
        $this->queryString = $this->queryString . " WHERE name = '$name' ";
        return $this;
    }

    public function insertInto($table, $paramNameString, $paramValuesString): QueryBuilder
    {
        $this->queryString = "INSERT INTO $table ($paramNameString) VALUES ($paramValuesString)";
        //var_dump($this->queryString);

        return $this;
    }

    public function update($table, $paramString): QueryBuilder
    {
        $this->queryString = "UPDATE $table SET $paramString";
        return $this;
    }

    public function delete(): QueryBuilder
    {
        $this->queryString = "DELETE ";
        return $this;
    }

//    public function getQuery(): string
//    {
//        return $this->queryString;
//    }

    public function __toString(): string
    {
        return $this->queryString;
    }


}