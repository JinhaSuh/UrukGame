<?php

namespace App\controller;

use App\exception\MyException;
use App\service\MapService;
use App\service\UserService;
use App\service\WeatherService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

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

    public function getUser(Request $request, Response $response)
    {
        $input = $request->getParsedBody();

        try {
            $result = $this->userService->select_user($input);
            $response->getBody()->write(json_encode($result));
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        } catch (MyException $e) {
            $response->getBody()->write(json_encode($e->jsonSerialize()));
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        }
    }

    public function fatigue(Request $request, Response $response)
    {
        $input = $request->getParsedBody();

        try {
            $result = $this->userService->buy_fatigue($input);
            $response->getBody()->write(json_encode($result));
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        } catch (MyException $e) {
            $response->getBody()->write(json_encode($e->jsonSerialize()));
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        }
    }

    public function getWeather(Request $request, Response $response)
    {
        try {
            $weather_data = $this->weatherService->select_weather_data();
            $response->getBody()->write(json_encode($weather_data));
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        } catch (MyException $e) {
            $response->getBody()->write(json_encode($e->jsonSerialize()));
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        }
    }

    public function getMap(Request $request, Response $response)
    {
        try {
            $map_list = $this->mapService->select_maps();
            $response->getBody()->write(json_encode($map_list));
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        } catch (MyException $e) {
            $response->getBody()->write(json_encode($e->jsonSerialize()));
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        }
    }
}
