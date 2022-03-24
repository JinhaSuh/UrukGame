<?php

use App\controller\AuctionController;
use App\controller\BoatController;
use App\controller\FishingController;
use App\controller\InventoryController;
use App\controller\RankingController;
use App\controller\ShopController;
use App\controller\UserController;
use App\controller\AccountController;
use App\controller\MailBoxController;
use App\controller\CollectionController;
use App\controller\WaterTankController;
use App\db\Plan_Data_Database;
use App\log\LogService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Slim\App;

return function (App $app) {

    $app->group('/account', function (Group $group) {
        //로그인
        $group->post('/login', AccountController::class . ':login');

        //회원가입
        $group->post('/signUp', AccountController::class . ':signUp');
    });

    //유저 정보 조회
    $app->post('/user', UserController::class . ':getUser');

    //피로도 구매
    $app->post('/buyFatigue', UserController::class . ':fatigue');

    //날씨 기획 데이터 조회
    $app->post('/weather', UserController::class . ':getWeather');

    //맵 기획 데이터 조회
    $app->post('/map', UserController::class . ':getMap');

    $app->group('/inventory', function (Group $group) {
        //인벤토리 조회
        $group->post('', InventoryController::class . ':getInventory');

        //채비 업그레이드
        $group->post('/upgrade', InventoryController::class . ':upgradeEquipment');

        //채비 장착
        $group->post('/equip', InventoryController::class . ':equipEquipment');

        //채비 장착 해제
        $group->post('/unequip', InventoryController::class . ':unequipEquipment');

        //장착한 채비 조회
        $group->post('/equipSlot', InventoryController::class . ':getEquipSlot');

        //장착한 내구도 수리
        $group->post('/repair', InventoryController::class . ':repairEquipment');
    });

    $app->group('/boat', function (Group $group) {
        //사용중인 배 조회
        $group->post('', BoatController::class . ':getBoat');

        //배 업그레이드
        $group->post('/upgrade', BoatController::class . ':upgradeBoat');

        //배 연료 구매
        $group->post('/refuel', BoatController::class . ':refuelBoat');
    });

    $app->group('/mailBox', function (Group $group) {
        //선물함 조회
        $group->post('', MailBoxController::class . ':getMailBox');

        //선물함 아이템 수령
        $group->post('/receive', MailBoxController::class . ':getMail');
    });

    //도감 조회
    $app->post('/collection', CollectionController::class . ':getColl');

    $app->group('/shop', function (Group $group) {
        //상점 조회
        $group->post('', ShopController::class . ':getShop');

        //상점 아이템 구매
        $group->post('/buy', ShopController::class . ':ItemBuy');
    });

    $app->group('/fishing', function (Group $group) {
        //낚시 시작
        $group->post('/start', FishingController::class . ':startFishing');

        //낚시 종료
        $group->post('/end', FishingController::class . ':endFishing');
    });

    //수조 조회
    $app->post('/waterTank', WaterTankController::class . ':getWaterTank');

    //출항
    $app->post('/departure', BoatController::class . ':departure');

    //입항
    $app->post('/arrival', BoatController::class . ':arrival');

    $app->group('/auction', function (Group $group) {
        //경매 조회
        $group->post('', AuctionController::class . ':getFishSellList');

        //경매에 물고기 판매
        $group->post('/sellFish', AuctionController::class . ':sellFish');
    });

    //주간 순위 조회
    $app->post('/weeklyRanking', RankingController::class. ':ranking');

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
