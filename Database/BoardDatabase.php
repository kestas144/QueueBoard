<?php

namespace QueueBoard\Database;

class BoardDatabase extends Database
{
    public function __construct(string $serverName, string $port, string $database, string $username, string $password)
    {
        parent::__construct($serverName, $port, $database, $username,$password);
        $this->createTables();
    }

    public function createTables()
    {
        if ($this->database->query("SHOW TABLES LIKE 'customers'")->rowCount() > 0) {
        } else {
            $sql = "CREATE TABLE customers(
                id VARCHAR(13) UNIQUE PRIMARY KEY,
                name VARCHAR(255) NOT NULL
                )
                ENGINE = InnoDB CHARACTER SET = utf8";
            $this->database->exec($sql);
        }

        if ($this->database->query("SHOW TABLES LIKE 'employees'")->rowCount() > 0) {
        } else {
            $sql = "CREATE TABLE employees(
                  id VARCHAR(13) UNIQUE PRIMARY KEY,
                  name VARCHAR(255) NOT NULL,
                  status ENUM('busy', 'free') NOT NULL,
                  customer_count VARCHAR(255) NOT NULL,
                  average_operation_time VARCHAR(255) NOT NULL
                  )
                  ENGINE = InnoDB CHARACTER SET = utf8";
            $this->database->exec($sql);
        }


        if ($this->database->query("SHOW TABLES LIKE 'board'")->rowCount() > 0) {
        } else {
            $sql = "CREATE TABLE board(
                  id INT(13) AUTO_INCREMENT PRIMARY KEY,
                  customer_id VARCHAR(13) NULL,
                  employee_id VARCHAR(13) NULL,
                  status ENUM('waiting', 'serviced', 'completed','canceled') NOT NULL,
                  countdown VARCHAR(255),
                  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                  updated_at TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                  CONSTRAINT FK_CustomerBoard FOREIGN KEY (customer_id) REFERENCES customers(id),
                  CONSTRAINT FK_EmployeeBoard FOREIGN KEY (employee_id) REFERENCES employees(id)
                  )
                  ENGINE = InnoDB CHARACTER SET = utf8";
            $this->database->exec($sql);
        }
    }


}

