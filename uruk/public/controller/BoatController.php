<?php

namespace controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use service\BoatService;

use Slim\App;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../service/BoatService.php';

class BoatController
{
    private BoatService $boatService;

    public function __construct()
    {
        $this->boatService = new BoatService();
    }

    public function selectBoat(Request $request, Response $response)
    {
        $input = $request->getParsedBody();

        try {
            $boat = $this->boatService->select_boat($input);
            $response->getBody()->write(json_encode($boat));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(200);
        } catch (\Exception $e) {
            $response->getBody()->write($e->getCode().": ". $e->getMessage());
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        }
    }

    public function upgradeBoat(Request $request, Response $response)
    {
        $input = $request->getParsedBody();

        try {
            $boat = $this->boatService->upgrade_boat($input);
            $response->getBody()->write(json_encode($boat));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(200);
        } catch (\Exception $e) {
            $response->getBody()->write($e->getCode().": ". $e->getMessage());
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        }
    }

    public function departure(Request $request, Response $response)
    {
        $input = $request->getParsedBody();

        try {
            $this->boatService->departure($input);
            $response->getBody()->write(json_encode(success));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(200);
        } catch (\Exception $e) {
            $response->getBody()->write($e->getCode().": ". $e->getMessage());
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        }
    }

    public function arrival(Request $request, Response $response)
    {
        $input = $request->getParsedBody();

        try {
            $this->boatService->arrival($input);
            $response->getBody()->write(json_encode(success));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(200);
        } catch (\Exception $e) {
            $response->getBody()->write($e->getCode().": ". $e->getMessage());
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        }
    }
}
