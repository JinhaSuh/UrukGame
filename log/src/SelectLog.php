<?php

use App\LogRepository;

require __DIR__ . '/../vendor/autoload.php';

$logRepository = new LogRepository();

$table_list = array("signup_log", "login_log", "departure_log", "arrival_log", "asset_log");

//SELECT 로그 DB
for ($j = 0; $j < count($table_list); $j++) {
    $result = $logRepository->select_table($table_list[$j]);
}

