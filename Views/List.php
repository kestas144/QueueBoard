<?php
?>
<body>
<div class="container">

        <form method="POST" action="/customerPage.php">

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
            <th>busena</th>
        </tr>
        </thead>
        <tbody>
    <?php
    foreach ($customers as $customer): ?>
        <tr>
            <th><?php echo $customer['id'] ?></th>
            <th><?php echo $customer['employee_id'] ?></th>
            <th><?php echo $customer['status'] ?></th>
        </tr>

    <?php endforeach;?>
</tbody>
</table>

</div>