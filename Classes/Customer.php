<?php
namespace QueueBoard\Database;
class Customer
{
    private $id;
    private $name;
    private $database;
    private $queryBuilder;


    public function __construct(Database $database, QueryBuilder $queryBuilder)
    {
        $this->database = $database;
        $this->queryBuilder = $queryBuilder;
    }

    public function decryptId()
    {
        $id = uniqid();
        while (!empty($checkId = $this->checkAvailableId($id)) == true) {
            $id = uniqid();
        }
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function checkAvailableId($id)
    {
        $data = $this->queryBuilder
            ->select()
            ->column('id')
            ->from('customers')
            ->where('id', $id);
        return $this->database->getData($data);
    }
    public function createCustomer()
    {
            $query = $this->queryBuilder->insertInto(
            'customers',
            'name, id',
            "'$this->name', '$this->id'"
        );
        $this->database->setData($query);
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

}