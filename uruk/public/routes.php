<?php

use controller\InventoryController;
use controller\UserController;
use controller\AccountController;
use DB\Config\Plan_Data_Database;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/controller/UserController.php';
require_once __DIR__ . '/controller/AccountController.php';
require_once __DIR__ . '/controller/InventoryController.php';

return function (App $app) {
    /*
     * TODO : 클래스 참조를 require_once를 제거하고 use만으로 가능하게 수정
     * TODO : api 묶기
    */
    //로그인
    $app->post('/account/login', function (Request $request, Response $response) {
        $accountController = new AccountController();
        return $accountController->login($request, $response);
    });

    //회원가입
    $app->post('/account/signUp', function (Request $request, Response $response) {
        $accountController = new AccountController();
        return $accountController->signUp($request, $response);
    });

    //유저 정보 조회
    $app->post('/user', function (Request $request, Response $response) {
        $userController = new UserController();
        return $userController->selectUser($request, $response);
    });

    //피로도 구매
    $app->post('/buyFatigue', function (Request $request, Response $response) {
        $userController = new UserController();
        return $userController->buyFatigue($request, $response);
    });

    //날씨 기획 데이터 조회
    $app->post('/weather', function (Request $request, Response $response) {
        $userController = new UserController();
        return $userController->selectWeatherData($request, $response);
    });

    //맵 기획 데이터 조회
    $app->post('/map', function (Request $request, Response $response) {
        $userController = new UserController();
        return $userController->selectMapData($request, $response);
    });

    //인벤토리 조회
    $app->post('/inventory', function (Request $request, Response $response) {
        $inventoryController = new InventoryController();
        return $inventoryController->selectInventory($request, $response);
    });

    //채비 업그레이드
    $app->post('/inventory/upgrade', function (Request $request, Response $response) {
        $inventoryController = new InventoryController();
        return $inventoryController->upgradeEquipment($request, $response);
    });

    //기획데이터 저장
    //PLAN_DATA(csv) upload to server DB
    //Usage example) GET : /upload/auction_data
    $app->get('/upload/{filename}', function (Request $request, Response $response) {
        $filename = $request->getAttribute('filename');

        //TODO : csv첫 행에 표현된 column 정보를 읽고 table 생성

        /*        $sql = "CREATE TABLE auction_data
                        (
                            fish_id int(11) NOT NULL,
                            iter_term int(11) NOT NULL,
                            min_sell_rate int(11) NOT NULL,
                            max_sell_rate int(11) NOT NULL,
                            change_range int(11) NOT NULL
                        );";

                $rs = $GLOBALS['pdo']->prepare($sql);
                try {
                    $rs->execute();
                    $response->getBody()->write(json_encode($rs));
                } catch (PDOException $e) {
                    $error = array(
                        "message" => $e->getMessage()
                    );
                    $response->getBody()->write(json_encode($error));
                    return $response
                        ->withHeader('content-type', 'application/json')
                        ->withStatus(500);
                }*/

        $sql2 = "LOAD DATA INFILE '" . $filename . ".table_data' INTO TABLE " . $filename . " 
                CHARACTER SET UTF8MB4
                FIELDS TERMINATED BY ','
                LINES TERMINATED BY '\r'
                IGNORE 1 LINES;";

        $db = new Plan_Data_Database();
        $conn = $db->getConnection();
        $rs = $conn->prepare($sql2);

        try {
            $rs->execute();
            $response->getBody()->write(json_encode($rs));
            $response->getBody()->write("\ninserted File, $filename");
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

        return $response;
    });
};

