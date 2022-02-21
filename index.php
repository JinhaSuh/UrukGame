<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

use DB\Config\Database;

use MyApp\Controller\MyController;

require __SERVER__ . '/../vendor/autoload.php';
require_once 'uruk/slimAndPDOStudy/dbconnect.php';

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello jinha world!");
    return $response;
});

$app->get('/hello/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");

    return $response;
});

//POST Route
$app->post('/insertUser', function ($request, $response, $args) {
    $data = $request->getParsedBody();
    $id = $data["id"];
    $nickname = $data["nickname"];

    $sql = "INSERT INTO users (id, nickname) VALUES (:id, :nickname)";

    try {
        $db = new Database();
        $pdo = $db->getConnection();

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nickname', $nickname);

        $result = $stmt->execute();

        $db = null;
        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    } catch (PDOException $e) {
        $error = array(
            "message" => $e->getMessage()
        );

        $response->getBody()->write(json_encode($error));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(500);
    }
});

$app->get('/method1', \MyController::class. ':method1');

$app->get('/dbtest', function (Request $request, Response $response) {
    $sql ="SELECT id, nickname FROM user";

    try {
        $db = new Database();
        $pdo = $db->getConnection();
        $stmt = $pdo->query($sql);
        $users = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        $response->getBody()->write(json_encode($users));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    } catch (PDOException $e) {
        $error = array(
            "message" => $e->getMessage()
        );

        $response->getBody()->write(json_encode($error));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(500);
    }
});

$app->get('/selectUser', function (Request $request, Response $response) {

    $sql = "SELECT id, nickname FROM user";
    $pdo = new PDO("mysql:host=localhost:3306;dbname=study_db;charset=utf8", "root", "Xowlsgk01!");

    try {
        $stmt = $pdo->query($sql);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($users) {
            // show the publishers
            foreach ($users as $user) {
                echo $user['nickname'] . '<br>';
            }
        }

    } catch (Exception $e) {
        if ($pdo->inTransaction())
            $pdo->rollBack();
        echo $e->getMessage();
    }
});

$app->run();
