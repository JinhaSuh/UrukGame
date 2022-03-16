<?php

namespace controller;

use service\MapService;
use service\UserService;
use service\WeatherService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../service/UserService.php';
require_once __DIR__ . '/../service/WeatherService.php';
require_once __DIR__ . '/../service/MapService.php';

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
        $input = $request->getParsedBody();

        try {
            $result = $this->userService->select_user($input);
            $response->getBody()->write(json_encode($result));
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        } catch (\Exception $e) {
            $response->getBody()->write($e->getCode().": ". $e->getMessage());
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        }
    }

    public function buyFatigue(Request $request, Response $response)
    {
        $input = $request->getParsedBody();

        try {
            $result = $this->userService->buy_fatigue($input);
            $response->getBody()->write(json_encode($result));
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        } catch (\Exception $e) {
            $response->getBody()->write($e->getCode().": ". $e->getMessage());
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        }
    }

    public function selectWeatherData(Request $request, Response $response)
    {
        try {
            $weather_data = $this->weatherService->select_weather_data();
            $response->getBody()->write(json_encode($weather_data));
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        } catch (\Exception $e) {
            $response->getBody()->write($e->getCode().": ". $e->getMessage());
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        }
    }

    public function selectMapData(Request $request, Response $response)
    {
        try {
            $map_list = $this->mapService->select_maps();
            $response->getBody()->write(json_encode($map_list));
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        } catch (\Exception $e) {
            $response->getBody()->write($e->getCode().": ". $e->getMessage());
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        }
    }
}
