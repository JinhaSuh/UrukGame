<?php

use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();
$app->addBodyParsingMiddleware();

//Router 등록
$routes = require __DIR__ . '/uruk/src/routes.php';
$routes($app);

$app->run();
