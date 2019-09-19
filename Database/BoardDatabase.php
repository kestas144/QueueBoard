<?php

namespace QueueBoard\Database;

class BoardDatabase extends Database
{
    public function __construct(string $serverName, string $port, string $database, string $username)
    {
        parent::__construct($serverName, $port, $database, $username);
        //$this->createDatabase();
        $this->createTables();
    }

    public function createDatabase()
    {
//        $check = $this->database->query
//        ("SELECT COUNT(*)
//                    FROM INFORMATION_SCHEMA.SCHEMATA
//                    WHERE SCHEMA_NAME = 'db_board'");
//        return (bool) $check->fetchColumn();

//       $check = $this->database->query("SHOW DATABASES LIKE 'dbname'");
//
//        if ($this->database->query()) {
//            echo "Database created successfully";
//        } else {
//            echo "Error creating database: " . mysqli_error($conn);
//        }
//        $sql = "CREATE DATABASE db_board";
    }


    public function createTables()
    {
        if ($this->database->query("SHOW TABLES LIKE 'customers'")->rowCount() > 0) {
        } else {
            $sql = "CREATE TABLE customers(
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL
                )
                ENGINE = InnoDB CHARACTER SET = utf8";
            $this->database->exec($sql);
        }

        if ($this->database->query("SHOW TABLES LIKE 'employees'")->rowCount() > 0) {
        } else {
            $sql = "CREATE TABLE employees(
                  id INT(11) AUTO_INCREMENT PRIMARY KEY,
                  name VARCHAR(255) NOT NULL,
                  status ENUM('busy', 'free') NOT NULL               
                  )
                  ENGINE = InnoDB CHARACTER SET = utf8";
            $this->database->exec($sql);
        }


        if ($this->database->query("SHOW TABLES LIKE 'board'")->rowCount() > 0) {
        } else {
            $sql = "CREATE TABLE board(
                  id INT(11) AUTO_INCREMENT PRIMARY KEY,
                  customer_id INT(11) NULL,
                  employee_id INT(11) NULL,
                  status ENUM('waiting', 'serviced', 'completed') NOT NULL,
                  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                  CONSTRAINT FK_CustomerBoard FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
                  CONSTRAINT FK_EmployeeBoard FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE
                  )
                  ENGINE = InnoDB CHARACTER SET = utf8";
            $this->database->exec($sql);
        }
    }


}

