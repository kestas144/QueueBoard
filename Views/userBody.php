<?php
if (isset($message)) {
    echo $message['success'];
}
if (isset($data)) {
    echo "<div>";
    echo "<table>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>numerelis</th>";
    echo "<th>langelis</th>";
    echo "<th>Busena</th>";
    if (isset($_GET['customerId'])) {
        echo "<th>Laikas</th>";
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








