<?php

namespace controller;

use service\FishingService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../service/FishingService.php';

class FishingController
{
    private FishingService $fishingService;

    public function __construct()
    {
        $this->fishingService = new FishingService();
    }

    public function startFishing(Request $request, Response $response)
    {
        $input = $request->getParsedBody();

        try {
            $this->fishingService->start_fishing($input);
            $response->getBody()->write(json_encode(success));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(200);
        } catch (\Exception $e) {
            $response->getBody()->write($e->getCode().": ". $e->getMessage());
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        }
    }

    public function endFishing(Request $request, Response $response)
    {
        $input = $request->getParsedBody();

        try {
            $fishing_result = $this->fishingService->end_fishing($input);
            $response->getBody()->write(json_encode($fishing_result));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(200);
        } catch (\Exception $e) {
            $response->getBody()->write($e->getCode().": ". $e->getMessage());
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        }
    }
}
