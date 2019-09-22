<?php
namespace QueueBoard\Database;

class QueryBuilder
{
    private $queryString = null;

    public function select(): QueryBuilder
    {
        $this->queryString = "SELECT";
        return $this;
    }

    public function column(string $columnName): QueryBuilder
    {
        if ($columnName == 'all') {

            $this->queryString = $this->queryString . " *";
        } else {
            $this->queryString = $this->queryString . " $columnName";
        }
        return $this;
    }

    public function from(string $table): QueryBuilder
    {
        $this->queryString = $this->queryString . " FROM $table";
        return $this;
    }

    public function where(string $whereName, string $whereValue): QueryBuilder
    {

        $this->queryString = $this->queryString . " WHERE " . $whereName . " = '" . "$whereValue'";
        return $this;
    }

    public function and(string $whereName, string $whereValue): QueryBuilder
    {
        $this->queryString = $this->queryString . " AND " . $whereName . " = '" . "$whereValue'" ;
        return $this;
    }
    public function or(string $whereName, string $whereValue): QueryBuilder
    {
        $this->queryString = $this->queryString . " OR " . $whereName . " = '" . "$whereValue'" ;
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