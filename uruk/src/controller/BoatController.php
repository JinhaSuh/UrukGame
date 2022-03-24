<?php

namespace App\controller;

use App\exception\MyException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\service\BoatService;

class BoatController
{
    private BoatService $boatService;

    public function __construct()
    {
        $this->boatService = new BoatService();
    }

    public function getBoat(Request $request, Response $response)
    {
        $input = $request->getParsedBody();

        try {
            $boat = $this->boatService->select_boat($input);
            $response->getBody()->write(json_encode($boat));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(200);
        } catch (MyException $e) {
            $response->getBody()->write(json_encode($e->jsonSerialize()));
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
        } catch (MyException $e) {
            $response->getBody()->write(json_encode($e->jsonSerialize()));
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        }
    }

    public function departure(Request $request, Response $response)
    {
        $input = $request->getParsedBody();

        try {
            $result = $this->boatService->departure($input);
            $response->getBody()->write(json_encode($result));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(200);
        } catch (MyException $e) {
            $response->getBody()->write(json_encode($e->jsonSerialize()));
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        }
    }

    public function arrival(Request $request, Response $response)
    {
        $input = $request->getParsedBody();

        try {
            $result = $this->boatService->arrival($input);
            $response->getBody()->write(json_encode($result));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(200);
        } catch (MyException $e) {
            $response->getBody()->write(json_encode($e->jsonSerialize()));
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        }
    }

    public function refuelBoat(Request $request, Response $response)
    {
        $input = $request->getParsedBody();

        try {
            $result = $this->boatService->refuel($input);
            $response->getBody()->write(json_encode($result));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(200);
        } catch (MyException $e) {
            $response->getBody()->write(json_encode($e->jsonSerialize()));
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        }
    }
}
