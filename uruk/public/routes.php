<?php

use controller\BoatController;
use controller\FishingController;
use controller\InventoryController;
use controller\UserController;
use controller\AccountController;
use controller\MailBoxController;
use controller\CollectionController;
use controller\WaterTankController;
use DB\Config\Plan_Data_Database;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Slim\App;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/controller/UserController.php';
require_once __DIR__ . '/controller/AccountController.php';
require_once __DIR__ . '/controller/InventoryController.php';
require_once __DIR__ . '/controller/BoatController.php';
require_once __DIR__ . '/controller/MailBoxController.php';
require_once __DIR__ . '/controller/CollectionController.php';
require_once __DIR__ . '/controller/FishingController.php';
require_once __DIR__ . '/controller/WaterTankController.php';

return function (App $app) {
    /*
     * TODO : 클래스 참조를 require_once를 제거하고 use만으로 가능하게 수정
    */

    $app->group('/account', function (Group $group) {
        //로그인
        $group->post('/login', AccountController::class . ':login');

        //회원가입
        $group->post('/signUp', AccountController::class . ':signUp');
    });

    //유저 정보 조회
    $app->post('/user', UserController::class. ':selectUser');

    //피로도 구매
    $app->post('/buyFatigue', UserController::class. ':buyFatigue');

    //날씨 기획 데이터 조회
    $app->post('/weather', UserController::class. ':selectWeatherData');

    //맵 기획 데이터 조회
    $app->post('/map', UserController::class. ':selectMapData');

    $app->group('/inventory', function (Group $group) {
        //인벤토리 조회
        $group->post('', InventoryController::class . ':selectInventory');

        //채비 업그레이드
        $group->post('/upgrade', InventoryController::class . ':upgradeEquipment');

        //채비 장착
        $group->post('/equip', InventoryController::class . ':equipEquipment');

        //장착한 채비 조회
        $group->post('/equipSlot', InventoryController::class . ':selectEquipSlot');
    });

    $app->group('/boat', function (Group $group) {
        //사용중인 배 조회
        $group->post('', BoatController::class . ':selectBoat');

        //배 업그레이드
        $group->post('/upgrade', BoatController::class . ':upgradeBoat');
    });

    $app->group('/mailBox', function (Group $group) {
        //선물함 조회
        $group->post('', MailBoxController::class . ':selectMailBox');

        //선물함 아이템 수령
        $group->post('/receive', MailBoxController::class . ':receiveMailBoxItem');
    });

    //도감 조회
    $app->post('/collection', CollectionController::class. ':selectCollection');

    $app->group('/fishing', function (Group $group) {
        //낚시 시작
        $group->post('/start', FishingController::class . ':startFishing');

        //낚시 종료
        $group->post('/end', FishingController::class . ':endFishing');
    });

    //수조 조회
    $app->post('/waterTank', WaterTankController::class. ':selectWaterTank');

    //출항
    $app->post('/departure', BoatController::class. ':departure');

    //입항
    $app->post('/arrival', BoatController::class. ':arrival');

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
