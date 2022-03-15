<?php

namespace controller;

use dto\User;
use service\MapService;
use service\UserService;
use service\WeatherService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Factory\AppFactory;
use exception\UserException;

require_once __DIR__ . '/../dto/User.php';
require_once __DIR__ . '/../service/UserService.php';
require_once __DIR__ . '/../service/WeatherService.php';
require_once __DIR__ . '/../service/MapService.php';
require_once __DIR__ . '/../exception/UserException.php';

class UserController
{
    private UserService $userService;
    private WeatherService $weatherService;
    private MapService $mapService;

    public function __construct()
    {
        $this->userService = new UserService();
        $this->weatherService = new WeatherService();
        $this->mapService = new MapService();
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
            $user = User::Deserialize($data);
            $result = $this->userService->buy_fatigue($user);
            $response->getBody()->write(json_encode($result));
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        } catch (\PDOException|UserException $e) {
            $response->getBody()->write($e->getCode() . $e->getMessage());
            return $response->withHeader('content-type', 'application/json')->withStatus(501);
        }
    }

    public function selectWeatherData(Request $request, Response $response)
    {
        try {
            $weather_data = $this->weatherService->select_weather_data();
            $response->getBody()->write(json_encode($weather_data));
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        } catch (\PDOException|UserException $e) {
            $response->getBody()->write($e->getMessage());
            return $response->withHeader('content-type', 'application/json')->withStatus(502);
        }
    }

    public function selectMapData(Request $request, Response $response)
    {
        try {
            $map_list = $this->mapService->select_maps();
            $response->getBody()->write(json_encode($map_list));
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        } catch (\PDOException|UserException $e) {
            $response->getBody()->write($e->getMessage());
            return $response->withHeader('content-type', 'application/json')->withStatus(502);
        }
    }
}
