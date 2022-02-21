<?php
require_once 'dbconnect.php';

$sql ="SELECT id,nickname FROM user";
$pdo = new PDO("mysql:host=localhost:3306;dbname=study_db;charset=utf8", "root","Xowlsgk01!");

try {
    $stmt = $pdo->query($sql);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($users) {
        // show the publishers
        foreach ($users as $user) {
            echo $user['nickname'] . '<br>';
        }
    }

} catch (Exception $e){
    if($pdo->inTransaction())
        $pdo->rollBack();
    echo $e->getMessage();
}
