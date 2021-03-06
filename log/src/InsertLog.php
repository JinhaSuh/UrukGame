<?php

use App\LogScan;
use App\LogRepository;

require __DIR__ . '/../vendor/autoload.php';

$logRepository = new LogRepository();

$loadLog = new LogScan();
$loadLog->listDirFiles("/game/log/scribe/default_primary");

$table_list = array("signup_log", "login_log", "departure_log", "arrival_log", "asset_log");

$log_file_list = array_diff($loadLog->getFiles(), $loadLog->getExcludedFiles());

//INSERT 로그 DB
for ($i = 0; $i < count($log_file_list); $i++) {
    for ($j = 0; $j < count($table_list); $j++) {
        if (strpos($log_file_list[$i], $table_list[$j]) !== false) {
            if ($file = fopen($log_file_list[$i], "r")) {
                while (!feof($file)) {
                    $log_line = fgets($file);
                    if ($log_line == false) break;
                    $msg = json_decode($log_line);
                    $logRepository->insert_table($table_list[$j], $msg);
                }
                fclose($file);
            }
            break;
        }
    }
}
