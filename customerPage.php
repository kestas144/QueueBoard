<?php
use QueueBoard\Classes\Board;
use QueueBoard\Database\BoardDatabase;
use QueueBoard\Database\Customer;
use QueueBoard\Database\QueryBuilder;

require_once 'Database/Database.php';
require_once 'Database/BoardDatabase.php';
require_once 'Database/QueryBuilder.php';
require_once 'Classes/Board.php';
require_once 'Classes/Customer.php';
require_once 'Views/header.php';
$config = include 'config.php';

if (isset($_POST['name']) || isset($_GET['customerId']) || isset($_GET['delayId']) || isset($_GET['cancelId'])) {
//   $database = new BoardDatabase("localhost", "3308", "db_board", "root", "");
    $database = new BoardDatabase(
        $config['host'],
        $config['port'],
        $config['database'],
        $config['username'],
        $config['password']);
    $builder = new QueryBuilder();
    if (isset($_POST['name'])) {
        $customer = new Customer($database,$builder);
        $customer->setName($_POST['name']);
        $customer->decryptId();
        $customer->createCustomer();
        $customerId = $customer->getId();
        $board = new Board($database, $builder,$customerId);
        $board->writeCustomerToBoard();

        $message['success'] = 'sėkmingai prisiregistruota';
    } elseif (isset($_GET['delayId'])||isset($_GET['cancelId'])) {
        $board = new Board($database, $builder);
        if(isset($_GET['delayId'])) {
            echo $board->postponeAppointment($_GET['delayId'],$_GET['customerId']);
        }
        else {
            $board->changeStatus($_GET['cancelId'],'canceled','board');
        }

        $data = $board->getEntryByCustomerId($_GET['customerId']);
    } else {
        $board = new Board($database, $builder);
        $data = $board->getEntryByCustomerId($_GET['customerId']);
    }
    require_once 'Views/userBody.php';
    require_once 'Views/userScripts.php';
}

require_once 'Views/footer.php';








