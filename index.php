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

$database = new BoardDatabase("localhost", "3308", "db_board", "root", "");
$builder = new QueryBuilder();

$board = new Board($database, $builder);
$board->changeStatusAll();
$customers = $board->getAllWaitingCustomers();

require_once 'Views/List.php';
require_once 'Views/footer.php';
?>


