<?php

namespace controller;

use service\WaterTankService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../service/WaterTankService.php';

class WaterTankController
{
    private WaterTankService $waterTankService;

    public function __construct()
    {
        $this->waterTankService = new WaterTankService();
    }

    public function selectWaterTank(Request $request, Response $response)
    {
        $input = $request->getParsedBody();

        try {
            $waterTank = $this->waterTankService->select_water_tank($input);
            $response->getBody()->write(json_encode($waterTank));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(200);
        } catch (\Exception $e) {
            $response->getBody()->write($e->getCode().": ". $e->getMessage());
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        }
    }
}
