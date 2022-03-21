<?php

namespace App\controller;

use App\exception\MyException;
use App\service\CollectionService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CollectionController
{
    private CollectionService $collectionService;

    public function __construct()
    {
        $this->collectionService = new CollectionService();
    }

    public function selectCollection(Request $request, Response $response)
    {
        $input = $request->getParsedBody();

        try {
            $select_account = $this->collectionService->select_collection($input);
            $response->getBody()->write(json_encode($select_account));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(200);
        } catch (MyException $e) {
            $response->getBody()->write(json_encode($e->jsonSerialize()));
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        }
    }
}
