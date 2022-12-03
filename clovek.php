<html>
<head>
    <meta charset="UTF-8">
    <!-- Bootstrap-->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<?php
$id = filter_input(INPUT_GET,
    'employeeId',
    FILTER_VALIDATE_INT,
    ["options" => ["min_range"=> 1]]
);


if ($id === null || $id === false) {
    http_response_code(400);
    $status = "bad_request";
} else {

    require_once "inc/db.inc.php";

    $stmt = $pdo->prepare("SELECT * FROM employee WHERE employee_id=:employeeId");
    $stmt->execute(['employeeId' => $id]);
        $lidi = $stmt->fetch();
        $status = "OK";

        $prijmeniKratke = substr($lidi->surname, 0, 1);

        $dalsiPrikaz = $pdo->prepare("SELECT name FROM room WHERE room_id =:roomId");
        $dalsiPrikaz->execute(['roomId' => $lidi->room]);
        $mistnost = $dalsiPrikaz->fetch();
        $nazevMistnosti = $mistnost->name;

    $mzda = number_format($lidi->wage,2);
    
    $roomKeys = $pdo->prepare("SELECT r.room_id AS room_id, r.name AS name FROM `key` k LEFT JOIN room r ON (r.room_id = k.room) WHERE k.employee=:employeeId");

    $roomKeys->execute(['employeeId' => $id]);
    $roomKeys = $roomKeys->fetchAll();


}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo "Karta osoby {$lidi->name} {$prijmeniKratke}."?></title>
</head>
<body class="container">
<?php
if ($id === null || $id === false) {
    http_response_code(400);
    $status = "bad_request";
} 
else
{
    switch ($status) {
        case "bad_request":
            echo "<h1>Error 400: Bad request</h1>";
            break;
        case "not_found":
            echo "<h1>Error 404: Not found</h1>";
            break;
        default:
            $odkazMistnosti = "room.php?roomId=$lidi->room";
            echo "<h1>Karta osoby: {$lidi->name} {$prijmeniKratke}.</h1>";
            echo "<dl class='dl-horizontal'>";
            echo "<dt>Jméno</dt> <dd>{$lidi->name}</dd>";
            echo "<dt>Příjmení</dt> <dd>{$lidi->surname}</dd>";
            echo "<dt>Pozice</dt> <dd>{$lidi->job}</dd>";
            echo "<dt>Mzda</dt> <dd>{$mzda} Kč</dd>";
            echo "<dt>Místnost</dt><dd><a href='".$odkazMistnosti."'>{$nazevMistnosti}</a></dd>"; 
            echo "<dt>Klíče</dt>";
            foreach($roomKeys as $room)
            {  
                echo "<dd>";
                $odkazNaMistnost = "room.php?roomId=$room->room_id";
                echo "<a href='".$odkazNaMistnost."'>{$room->name}</a><br>";
                echo "</dd>";
            }
            echo "</dl>";
            echo "<a href='employees.php'><span class='glyphicon glyphicon-arrow-left' aria-hidden='true'></span> Zpět na seznam zaměstnanců</a>";
            break;
    }
}
?>  
</body>
</html>