<?php

namespace App\controller;

use App\exception\MyException;
use App\service\RankingService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class RankingController
{
    private RankingService $rankingService;

    public function __construct()
    {
        $this->rankingService = new RankingService();
    }

    public function ranking(Request $request, Response $response)
    {
        $input = $request->getParsedBody();

        try {
            $ranking = $this->rankingService->select_ranking($input);
            $response->getBody()->write(json_encode($ranking));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(200);
        } catch (MyException $e) {
            $response->getBody()->write(json_encode($e->jsonSerialize()));
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        }
    }
}
