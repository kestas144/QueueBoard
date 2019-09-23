<?php
if (isset($data)) {
    echo "<div id='userTable' class='container'>";
    echo "<table class='table'>";
    echo "<thead class='thead-light'>";
    echo "<tr>";
    echo "<th scope='col'>Numerėlis</th>";
    echo "<th scope='col'>Langelis</th>";
    echo "<th scope='col'>Būsena</th>";
    if (isset($_GET['customerId'])) {
        echo "<th>Numatomas kvietimo laikas</th>";
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
        switch ($customer['status']) {
            case 'waiting':
                echo "<td>Laukti</td>";
                break;
            case 'completed':
                echo "<td>Aptarnautas</td>";
                break;
            case "serviced";
                echo "<td>Prieiti</td>";
                break;
            case "canceled";
                echo "<td>Atšaukta</td>";
                break;
            default:
                break;
        }
        if (isset($_GET['customerId'])) {
            $date = $customer['created_at'];
            $timeCreated = strtotime($customer['created_at']);
            $time = $timeCreated + $customer['countdown'];
            echo "<td>" . date("Y-m-d H:i:s", $time) . "</td>";
            echo "<td>";
            echo "<button onClick = 'refreshPageWithDelayParameter(" . $customer['employee_id'] . ")'> Pavėlinti </button>";
            echo "</td >";
            echo "<td>";
            echo "<button onClick = 'refreshPageWithCancelParameter(" . $customer['id'] . ")'> Atšaukti </button>";
            echo "</td >";
        } else {
            if($customer['status'] != "completed") {
                echo "<td>";
                echo "<button onClick = 'refreshPageWithCompletedParameter(" . $customer['id'] . ")'> Aptarnautas </button>";
                echo "</td >";
            }else{
                echo "<td></td>";
            }
        }


    }
    echo "</tbody>";
    echo "</table>";
    echo "</div>";
    if (isset($message)) {
        echo "<h6 align='center'> $message  </h6>";
    }
    echo "<a href='/index.php' 
    class='btn btn-primary btn-lg active' 
    role='button' 
    aria-pressed='true'>Grįžti į pradinį puslapį</a>";
}








