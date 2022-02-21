<?php
//echo __DIR__;
$dbconfig = require_once __DIR__.'/dbconfig.php';
//echo $dbconfig['server'];

try {
    $pdo = new PDO(
        "mysql:host=" . $dbconfig['server'] . ";dbname=" . $dbconfig['name'] . ";
        charset=" . $dbconfig['charset'], $dbconfig['username'], $dbconfig['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (Exception $e) {
    echo 'db connection error';
}
