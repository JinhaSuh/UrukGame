<?php

namespace controller;

use dto\User;
use service\UserService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Factory\AppFactory;
use exception\UserException;

require_once __DIR__ . '/../dto/User.php';
require_once __DIR__ . '/../service/UserService.php';
require_once __DIR__ . '/../exception/UserException.php';

class UserController
{
    private UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function selectAllUser(Request $request, Response $response)
    {
        $users = $this->userService->select_users();

        $response->getBody()->write(json_encode($users));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function selectUser(Request $request, Response $response)
    {
        $requestBody = $request->getParsedBody();
        $data = json_decode((string)json_encode($requestBody), false);

        //필수 입력값을 입력받았는지 확인
        if (!isset($data->user_id)) {
            $userException = new UserException("요청 형식이 잘못되었습니다.", 510);
            $response->getBody()->write($userException->getMessage());
            return $response->withHeader('content-type', 'application/json')->withStatus(510);
        }

        try {
            $user = User::Deserialize($data);
            $result = $this->userService->select_user($user);
            $selected_user = User::Deserialize($result);
            $response->getBody()->write(json_encode($selected_user));
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        } catch (\PDOException|UserException $e) {
            $response->getBody()->write($e->getMessage());
            return $response->withHeader('content-type', 'application/json')->withStatus(502);
        }
    }

    public function buyFatigue(Request $request, Response $response)
    {
        $requestBody = $request->getParsedBody();
        $data = json_decode((string)json_encode($requestBody), false);

        //필수 입력값을 입력받았는지 확인
        if (!isset($data->user_id)) {
            $userException = new UserException("요청 형식이 잘못되었습니다.", 510);
            $response->getBody()->write($userException->getMessage());
            return $response->withHeader('content-type', 'application/json')->withStatus(510);
        }

        try {
            //유저 정보 가져오기
            $user = User::Deserialize($data);
            $result = $this->userService->select_user($user);
            $selected_user = User::Deserialize($result);

            //레벨 기획 데이터 가져오기
            $current_level_data = $this->userService->select_level_data($selected_user->get_level());
            $maxFatigue = $current_level_data["max_fatigue"];

            //골드를 소비하고, 피로도를 증가
            $selected_user->set_fatigue($selected_user->get_fatigue() + 5);
            $selected_user->set_gold($selected_user->get_gold() - 100);
            if ($selected_user->fatigue >= $maxFatigue) throw new UserException("충전 시 최대 피로도를 초과합니다.", 555);
            if ($selected_user->gold < 0) throw new UserException("골드가 부족합니다.", 607);

            $updated_user = $this->userService->update_user($selected_user);
            $response->getBody()->write(json_encode($updated_user));
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        } catch (\PDOException|UserException $e) {
            $response->getBody()->write($e->getCode().$e->getMessage());
            return $response->withHeader('content-type', 'application/json')->withStatus(501);
        }
    }
}
