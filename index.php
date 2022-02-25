<?php

use Slim\Factory\AppFactory;
use Slim\App;

require __SERVER__ . '/../vendor/autoload.php';

$app = AppFactory::create();
$app->addBodyParsingMiddleware();

//Router 등록
$routes = require __DIR__ . '/uruk/public/routes.php';
$routes($app);

$app->run();
