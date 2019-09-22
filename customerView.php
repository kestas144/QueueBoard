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


if (isset($_POST['name']) || isset($_GET['customerId']) ||isset($_GET['delayId'])) {
    $database = new BoardDatabase("localhost", "3308", "db_board", "root", "");
    $builder = new QueryBuilder();
    if (isset($_POST['name'])) {
        $customer = new Customer();
        $customer->setName($_POST['name']);
        $board = new Board($database, $builder, $customer);
        $board->writeCustomerToBoard();
    } else {
        $board = new Board($database, $builder);
        $data = $board->getEntryByCustomerId($_GET['customerId']);
    }
    $data = $board->postponeAppointment($_GET['customerId']);
}
?>


<html>
<body>

<div>
    <table>
        <thead>
        <tr>
            <th>numerelis</th>
            <th>langelis</th>
            <th>Busena</th>
            <th>Laikas</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($data as $customer): ?>
            <tr>
                <th> <?php echo $customer['customer_id'] ?></th>
                <th> <?php echo $customer['employee_id'] ?></th>
                <th> <?php echo $customer['status'] ?></th>
                <th> <?php echo $customer['countdown'] ?></th>
                <th>
                    <button
                        onClick="refreshPageWithParameters(<?php echo $customer['id'] ?>)">Pavėlinti
                    </button>
                </th>
            </tr>


        <?php endforeach; ?>
</tbody>
</table>
</div>

<script>

    function refreshPageWithParameters(boardId) {
        if (window.location.href.indexOf("&delayId") > -1) {
            window.location.href = location.search.replace(/&delayId=[^&$]*/i, '&delayId=' + boardId );
        } else {
            window.location.href = location.href + "&delayId=" + boardId ;
        }
    }


</script>
</body>
</html>


