<?php
use QueueBoard\Classes\Board;
use QueueBoard\Database\BoardDatabase;
use QueueBoard\Database\Customer;
use QueueBoard\Database\QueryBuilder;

require_once  'Database/Database.php';
require_once  'Database/BoardDatabase.php';
require_once  'Database/QueryBuilder.php';
require_once  'Classes/Board.php';
require_once  'Classes/Customer.php';

if (!empty($_POST['name'])){
    $database = new BoardDatabase("localhost", "3308","db_board", "root", "");
    $builder = new QueryBuilder();
    $customer = new Customer();
    $customer->setName($_POST['name']);
    $board = new Board($database, $builder,$customer);
    $board->setEmployeeId();
    $board->writeCustomerToBoard();
} else {
    echo "nieko neivedėte";
}