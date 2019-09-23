<?php
use QueueBoard\Classes\Board;
use QueueBoard\Database\BoardDatabase;
use QueueBoard\Database\QueryBuilder;

require_once 'Database/Database.php';
require_once 'Database/BoardDatabase.php';
require_once 'Database/QueryBuilder.php';
require_once 'Classes/Board.php';
require_once 'Classes/Customer.php';
require_once 'Views/header.php';
$config = include 'config.php';

if (isset($_GET['employeeId'])) {

    $database = new BoardDatabase(
        $config['host'],
        $config['port'],
        $config['database'],
        $config['username'],
        $config['password']);
    $builder = new QueryBuilder();
    $board = new Board($database, $builder, null, $_GET['employeeId']);
    if (isset($_GET['completedId'])) {
        $data = $board->getEmployeeWaitingCustomers($_GET['completedId']);
    } else {
        $data = $board->getEmployeeWaitingCustomers();
    }
    require_once 'Views/userBody.php';
    require_once 'Views/userScripts.php';
}

require_once 'Views/footer.php';





