<?php
require_once("pdo_connect.php");

$name = $_POST['name'];
try {
    $query = "insert into table1 (id,name) values (4, '$name')";
    $PDO->exec($query);
    echo "insert".$query;
} catch (PDOException $e) {
    echo "<html><body><h1>ERR:" . $e->getMessage() + "</h1></body></html>";
}
