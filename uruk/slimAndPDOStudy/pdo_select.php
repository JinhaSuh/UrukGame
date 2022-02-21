<?php
require_once("pdo_connect.php");

$sql = "SELECT * FROM table1 WHERE id=:id";
$id = '1';

$rs = $PDO->prepare($sql);
$rs->bindValue(':id', $id);
$rs->execute();
$row = $rs->fetch(PDO::FETCH_ASSOC);
