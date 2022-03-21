<?php

namespace App\controller;

use App\exception\MyException;
use App\service\FishingService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

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
            $result = $this->fishingService->start_fishing($input);
            $response->getBody()->write(json_encode($result->jsonSerialize()));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(200);
        } catch (MyException $e) {
            $response->getBody()->write(json_encode($e->jsonSerialize()));
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
        } catch (MyException $e) {
            $response->getBody()->write(json_encode($e->jsonSerialize()));
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        }
    }
}
