<?php

namespace App\controller;

use App\exception\MyException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\service\ShopService;

class ShopController
{
    private ShopService $shopService;

    public function __construct()
    {
        $this->shopService = new ShopService();
    }

    public function getShop(Request $request, Response $response)
    {
        $input = $request->getParsedBody();

        try {
            $shop = $this->shopService->select_shop($input);
            $response->getBody()->write(json_encode($shop));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(200);
        } catch (MyException $e) {
            $response->getBody()->write(json_encode($e->jsonSerialize()));
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        }
    }

    public function ItemBuy(Request $request, Response $response)
    {
        $input = $request->getParsedBody();

        try {
            $result = $this->shopService->buy_shop_item($input);
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
