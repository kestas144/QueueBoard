<?php
namespace QueueBoard\Database;
use PDO;

abstract class Database
{
    protected $connection;
    protected $database;

    public function __construct(string $serverName, string $port, string $database, string $username)
    {
        $this->database =
            new PDO("mysql:host=" . $serverName . ";
            port=" . $port . ";
            dbname=" . $database . "", $username);

        $this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    }

    public function getData(string $query)
    {
        $prepared = $this->database->prepare($query);
        $prepared->execute();
        $result = $prepared->fetchAll(PDO::FETCH_ASSOC);
        return $result; //return all statement
    }

    public function setData(QueryBuilder $query): void
    {
        $prepared = $this->database->prepare($query);
        $prepared->execute();
    }

}