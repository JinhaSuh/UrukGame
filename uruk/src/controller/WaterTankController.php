<?php

namespace App\controller;

use App\exception\MyException;
use App\service\WaterTankService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class WaterTankController
{
    private WaterTankService $waterTankService;

    public function __construct()
    {
        $this->waterTankService = new WaterTankService();
    }

    public function getWaterTank(Request $request, Response $response)
    {
        $input = $request->getParsedBody();

        try {
            $waterTank = $this->waterTankService->select_water_tank($input);
            $response->getBody()->write(json_encode($waterTank));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(200);
        } catch (MyException $e) {
            $response->getBody()->write(json_encode($e->jsonSerialize()));
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        }
    }
}
