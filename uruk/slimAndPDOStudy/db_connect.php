<?php
require_once("uruk/config/db_config.php");

try {
    $PDO = new PDO('mysql;host=' . $host . ':' . $port . ';dbname=' . $database . ';charset=utf8', $user, $password);
    $PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $PDO->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $Exception) {
    die('연결 실패: ' . $Exception->getMessage());
}
