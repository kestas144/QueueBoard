<?php
if (isset($message)) {
    echo $message['success'];
}


if (isset($data)) {
    echo "<div class='container'>";
    echo "<table class='table'>";
    echo "<thead class='thead-light'>";
    echo "<tr>";
    echo "<th scope='col'>Numerėlis</th>";
    echo "<th scope='col'>Langelis</th>";
    echo "<th scope='col'>Būsena</th>";
    if (isset($_GET['customerId'])) {
        echo "<th>Laikas</th>";
        echo "<th></th>";
        echo "<th></th>";
        echo "</tr>";
    } else {
        echo "<th></th>";
        echo "<th></th>";
        echo "</tr>";
    }

    echo "</tr>";

    echo "</thead>";
    echo "<tbody>";

    foreach ($data as $customer) {
        echo "<tr>";
        echo "<td>" . $customer['id'] . "</td>";
        echo "<td>" . $customer['employee_id'] . "</td>";
        echo "<td>" . $customer['status'] . "</td>";

        if (isset($_GET['customerId'])) {
            echo "<td>" . $customer['countdown'] . "</td>";
            echo "<td>";
            echo "<button onClick = 'refreshPageWithDelayParameter(" . $customer['employee_id'] . ")'> Pavėlinti </button>";
            echo "</td >";
            echo "<td>";
            echo "<button onClick = 'refreshPageWithCancelParameter(" . $customer['id'] . ")'> Atšaukti </button>";
            echo "</td >";
        } else {
            echo "<td>";
            echo "<button onClick = 'refreshPageWithCompletedParameter(" . $customer['id'] . ")'> Aptarnautas </button>";
            echo "</td >";
        }


    }
    echo "</tbody>";
    echo "</table>";
    echo "</div>";
}








