<?php

use App\LogScan;
use App\LogRepository;

require __DIR__ . '/../vendor/autoload.php';

$logRepository = new LogRepository();

$loadLog = new LogScan();
$loadLog->listDirFiles("/game/log/scribe/default_primary");

$table_list = array("signup_log", "login_log", "departure_log", "arrival_log", "asset_log");

$log_file_list = $loadLog->getFiles();

//CREATE log tables
for ($i = 0; $i < count($log_file_list); $i++) {
    for ($j = 0; $j < count($table_list); $j++) {
        if (strpos($log_file_list[$i], $table_list[$j]) !== false) {
            $logRepository->create_table($table_list[$j]);
            break;
        }
    }
}
