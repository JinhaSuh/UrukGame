<?php
require_once("uruk/config/db_config.php");

$name = $_POST['name'];
try {
    $query = "insert into table1 (id,name) values (4, '$name')";
    $host->exec($query);
    echo "insert".$query;
} catch (PDOException $e) {
    echo "<html><body><h1>ERR:" . $e->getMessage() + "</h1></body></html>";
}
