<?php
require_once "inc/db.inc.php";
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <!-- Bootstrap-->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <title>Seznam zaměstnanců</title>
</head>
<body class="container">
<?php

    function OrderByWhat($poradi){   
        $order = "";
        switch($poradi){
            case "jmeno_up":
                $order = "employee.name ASC";
                break;
            case "jmeno_down":
                $order = "employee.name DESC";
                break;
            case "mistnost_up":
                $order = "room.name ASC";
                break;
            case "mistnost_down":
                $order = "room.name DESC";
                break;    
            case "telefon_up":
                $order = "room.phone ASC";
                break;
            case "telefon_down":
                $order = "room.phone DESC";
                break;
            case "prace_up":
                $order = "employee.job ASC";
                break;
            case "prace_down":
                $order = "employee.job DESC";
                break;
            default:
                $order = "employee.name ASC";
                break;
        } 
        return $order;
    }
    
    if(!isset($_GET['poradi'])) 
    {
        $order = "employee.name DESC";
    }
    else  
    {
        $order = OrderByWhat($_GET['poradi']);
    }
    
    
    
    
    
    
    
    $stmt = $pdo->query("SELECT employee.employee_id as employee_id, employee.name as name, employee.surname as surname, employee.job as job, room.phone as phone, room.name AS room FROM employee, room WHERE employee.room = room.room_id ORDER BY $order");
    
    
    if ($stmt->rowCount() == 0) {
        echo "Záznam neobsahuje žádná data";
    } else {
        echo "<h1>Seznam zaměstnanců</h1>";
        echo "<table class='table'><tbody>";
    
        echo "<th>Jméno<a href='?poradi=jmeno_up' class='sorted'><span class='glyphicon glyphicon-arrow-down' aria-hidden='true'></span></a><a href='?poradi=jmeno_down'><span class='glyphicon glyphicon-arrow-up' aria-hidden='true'></span></a></th>";
        echo "<th>Místnost<a href='?poradi=mistnost_up'><span class='glyphicon glyphicon-arrow-down' aria-hidden='true'></span></a><a href='?poradi=mistnost_down'><span class='glyphicon glyphicon-arrow-up' aria-hidden='true'></span></a></th>";
        echo "<th>Telefon<a href='?poradi=telefon_up'><span class='glyphicon glyphicon-arrow-down' aria-hidden='true'></span></a><a href='?poradi=telefon_down'><span class='glyphicon glyphicon-arrow-up' aria-hidden='true'></span></a></th>";
        echo "<th>Práce<a href='?poradi=prace_up'><span class='glyphicon glyphicon-arrow-down' aria-hidden='true'></span></a><a href='?poradi=prace_down'><span class='glyphicon glyphicon-arrow-up' aria-hidden='true'></span></a></th></tr>";
        while ($row = $stmt->fetch()) {
            echo "<tr>";
            echo "<td><a href='clovek.php?employeeId={$row->employee_id}'>{$row->name} {$row->surname}</a></td><td>{$row->room}</td><td>{$row->phone}</td><td>{$row->job}</td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
    }
    unset($stmt);
?>
</body>
</html> 