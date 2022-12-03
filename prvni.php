<!DOCTYPE html>

<html>
<head>
    <meta charset="UTF-8">
    <!-- Bootstrap-->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <title>Prohlížeč databáze</title>
</head>
<body class="container">
<?php
    echo "<div class='container'>";
    echo "<h1>Prohlížeč databáze</h1>";
    echo "<ul class='list-group'>";
    echo "<li class='list-group-item'><a href='employees.php?poradi=jmeno_up'>Seznam zaměstnanců</a></li>";
    echo "<li class='list-group-item'><a href='rooms.php'>Seznam místností</a></li>";
    echo "</ul>";
?>
</body>
</html>