<?php

namespace App\controller;

use App\exception\MyException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\service\AuctionService;

class AuctionController
{
    private AuctionService $auctionService;

    public function __construct()
    {
        $this->auctionService = new AuctionService();
    }

    public function selectAuction(Request $request, Response $response)
    {
        $input = $request->getParsedBody();

        try {
            $auction = $this->auctionService->select_auction($input);
            $response->getBody()->write(json_encode($auction));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(200);
        } catch (MyException $e) {
            $response->getBody()->write(json_encode($e->jsonSerialize()));
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        }
    }

    public function sellFish(Request $request, Response $response)
    {
        $input = $request->getParsedBody();

        try {
            $result = $this->auctionService->sell_fish($input);
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
