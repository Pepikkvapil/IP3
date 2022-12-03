<head>
    <meta charset="UTF-8">
    <!-- Bootstrap-->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<?php
$id = filter_input(INPUT_GET,
    'roomId',
    FILTER_VALIDATE_INT,
    ["options" => ["min_range"=> 1]]
);


if ($id === null || $id === false) {
    http_response_code(400);
    $status = "bad_request";
} else {

    require_once "inc/db.inc.php";

    $stmt = $pdo->prepare("SELECT * FROM room WHERE room_id=:roomId");
    $stmt->execute(['roomId' => $id]);
        $room = $stmt->fetch();
        $status = "OK";

    $employees = $pdo->prepare("SELECT name, surname, employee_id FROM employee WHERE room =:roomId");
    $employees->execute(['roomId' => $id]);
        $employees = $employees->fetchAll();
        $status = "OK";

    $employeeKeys = $pdo->prepare("SELECT e.employee_id AS employee_id, e.name AS name, e.surname AS surname FROM `key` as k LEFT JOIN employee as e ON (e.employee_id = k.employee) WHERE k.room =:roomId");
    $employeeKeys->execute(['roomId' => $id]);
        $employeeKeys = $employeeKeys->fetchAll();
        $status = "OK";
    
    $averageSalary = $pdo->prepare("SELECT AVG(wage) as avg_salary from employee WHERE room =:roomId");
    $averageSalary->execute(['roomId' => $id]);
        $avgSalary = $averageSalary->fetch();
        $status = "OK";
        $prumernaMzda = number_format($avgSalary->avg_salary,2);
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo "Karta místnosti č.{$room->no}"?></title>
</head>
<body class="container">
<?php
switch ($status) {
    case "bad_request":
        echo "<h1>Error 400: Bad request</h1>";
        break;
    case "not_found":
        echo "<h1>Error 404: Not found</h1>";
        break;
    default:
        echo "<h1>Místnost č. {$room->no}</h1>";
        echo "<dl class='dl-horizontal'>";
        echo "<dt>Číslo</dt> <dd>{$room->no}</dd>";
        echo "<dt>Název</dt> <dd>{$room->name}</dd>";
        if ($room->phone == null){
            echo "<dt>Telefon</dt> <dd>-</dd>";
        }
        else{
            echo "<dt>Telefon</dt> <dd>{$room->phone}</dd>";
        }
        echo "<dt>Lidé</dt>";
        if($employees == null)
        {
            echo "<dd>";
            echo "-";
            echo "</dd>";
        }
        else{
            foreach($employees as $clovek)
            {
                echo "<dd>";
                $odkazNaLidi = "clovek.php?employeeId={$clovek->employee_id}";
                $prijmeniKratke = substr($clovek->surname, 0, 1);
                echo "<a href='".$odkazNaLidi."'>{$clovek->name} {$prijmeniKratke}.</a><br>";
                echo "</dd>";
            }
            
        }
        if(count($employees) === NULL){
            echo "<dt>Průměrná mzda</dt> <dd>-</dd>";
        }
        else{
            echo "<dt>Průměrná mzda</dt> <dd>{$prumernaMzda} Kč</dd>";
        }
        echo "<dt>Klíče</dt>";
        foreach($employeeKeys as $clovek)
        {
            echo "<dd>";
            $odkazNaLidi = "clovek.php?employeeId={$clovek->employee_id}";
            $prijmeniKratke = substr($clovek->surname, 0, 1);
            echo "<a href='".$odkazNaLidi."'>{$clovek->name} {$prijmeniKratke}.</a><br>";
            echo "</dd>";
        }
        echo "</dl>";
        echo "<a href='rooms.php'><span class='glyphicon glyphicon-arrow-left' aria-hidden='true'></span> Zpět na seznam místností</a>";
        break;
}
?>
</body>
</html>