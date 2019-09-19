<?php
use QueueBoard\Classes\Board;
use QueueBoard\Database\BoardDatabase;
use QueueBoard\Database\QueryBuilder;


require_once  'Database/Database.php';
require_once  'Database/BoardDatabase.php';
require_once  'Database/QueryBuilder.php';
require_once  'Classes/Board.php';
require_once  'Classes/Customer.php';

$database = new BoardDatabase("localhost", "3308","db_board", "root", "");
$builder = new QueryBuilder();

$board = new Board($database, $builder);

$customers = $board->getAllWaitingCustomers();




?>
<html>
<body>
<div class="container">

        <form method="POST" action="/testView.php">

        <label for="name">Name</label>
        <input type="text" name="name" placeholder="Your name..">
        <input type="submit" value="Register">

    </form>
</div>
<div>
    <table>
        <thead>
        <tr>
            <th>numerelis</th>
            <th>langelis</th>
        </tr>
        </thead>
        <tbody>
    <?php
    foreach ($customers as $customer): ?>
        <tr>
            <th><?php echo $customer['customer_id'] ?></th>
            <th><?php echo $customer['employee_id'] ?></th>
        </tr>

<?php endforeach;?>
    </tbody>
    </table>

</div>
</body>
</html>
