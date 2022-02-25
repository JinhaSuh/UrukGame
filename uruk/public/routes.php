<?php

use DB\Config\Game_Data_Database;
use DB\Config\Plan_Data_Database;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\App;

//TODO : 하나의 클래스로 묶기
require_once("Game_Data_Database.php");
require_once("Plan_Data_Database.php");

return function (App $app) {

    //회원가입
    $app->post('/account/signUp', function (Request $request, Response $response) {
        $data = $request->getParsedBody();
        $player_id = $data["player_id"];
        $nation = $data["nation"];
        $language = $data["language"];

        $sql = "INSERT INTO account (player_id, nation, language) VALUES (:player_id, :nation, :language)";

        try {
            $db = new Game_Data_Database();
            $conn = $db->getConnection();

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':player_id', $player_id);
            $stmt->bindParam(':nation', $nation);
            $stmt->bindParam(':language', $language);

            $result = $stmt->execute();

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

    //유저 정보 조회
    $app->post('/user', function (Request $request, Response $response) {
        $data = $request->getParsedBody();
        $user_id = $data["userId"];

        $sql = "SELECT * FROM user WHERE user_id =:userId";

        try {
            $db = new Game_Data_Database();
            $conn = $db->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':userId', $user_id);
            $stmt->execute();

            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $payload = json_encode($data);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
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
}
?>
