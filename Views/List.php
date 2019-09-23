<?php
?>
<body>

<div class="container" id="formContainer">
    <h2>Registracijos forma</h2>
    <form action="/customerPage.php" method="post">
        <div class="form-group">
            <label for="name">Vardas:</label>
            <input type="text" class="form-control" id="name" placeholder="Įveskite savo vardą" name="name">
        </div>
        <button type="submit" class="btn btn-primary">Registruotis</button>
    </form>
</div>

<div class="container">
    <table id="tableName" class="table">
        <thead class="thead-light">
        <tr>
            <th scope="col">Numerėlis</th>
            <th scope="col">Langelis</th>
            <th scope="col">Būsena</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($customers as $key => $customer): ?>
            <tr>
                <th><?php echo $customer['id'] ?></th>
                <th><?php echo $customer['employee_id'] ?></th>
                <th><?php if ($customer['status'] == 'waiting') {
                        echo 'Palaukti';
                    } else {
                        echo 'Prieiti';
                    } ?></th>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</div>

<div class="container" id="formContainer">
    <h4>Pasitikrinkite apsilankymo laiką</h4>
    <form action="/customerPage.php" method="get">
        <div class="form-group">
            <label for="id">Sugeneruotas Id</label>
            <input type="text" class="form-control" id="name" placeholder="Įveskite savo Id" name="customerId">
        </div>
        <button type="submit" class="btn btn-primary">Tikrinti</button>
    </form>
</div>




