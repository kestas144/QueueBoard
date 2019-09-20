<?php
use QueueBoard\Classes\Board;
use QueueBoard\Database\BoardDatabase;
use QueueBoard\Database\QueryBuilder;


require_once 'Database/Database.php';
require_once 'Database/BoardDatabase.php';
require_once 'Database/QueryBuilder.php';
require_once 'Classes/Board.php';


if (isset($_GET['employeeId'])) {
    $database = new BoardDatabase("localhost", "3308", "db_board", "root", "");
    $builder = new QueryBuilder();
    $board = new Board($database, $builder, null, $_GET['employeeId']);
    if (isset($_GET['boardId'])){
        $customers = $board->getEmployeeWaitingCustomers($_GET['boardId']);
    } else {

        $customers = $board->getEmployeeWaitingCustomers();
    }

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
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($customers as $customer): ?>
            <tr>
                <th> <?php echo $customer['customer_id'] ?></th>
                <th> <?php echo $customer['employee_id'] ?></th>
                <th> <?php echo $customer['status'] ?></th>
                <th>
                    <button
                            onClick="refreshPageWithParameters(<?php echo $customer['id'] ?>)">Aptarnautas
                    </button>
                </th>
            </tr>


        <?php endforeach; ?>
        </tbody>
    </table>

</div>
<script>

    function refreshPageWithParameters(boardId) {
        if (window.location.href.indexOf("&boardId") > -1) {
            window.location.href = location.search.replace(/&boardId=[^&$]*/i, '&boardId=' + boardId);
        } else {
            window.location.href = location.href + "&boardId=" + boardId;
        }
    }


</script>
</body>
</html>
