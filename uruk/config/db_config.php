<?php
$host = 'localhost';
$port = '3306';
$database = 'PLAN_DATA';
$user = 'root';
$password = 'Xowlsgk01!';

try {
    $PDO = new PDO('mysql;host=' . $host . ':' . $port . ';dbname=' . $database . ';charset=utf8', $user, $password);
    $PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $PDO->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    print_r($PDO);
} catch (PDOException $Exception) {
    die('연결 실패: ' . $Exception->getMessage());
}

?>
