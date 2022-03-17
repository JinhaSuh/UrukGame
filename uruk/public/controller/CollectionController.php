<?php

namespace controller;

use service\CollectionService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../service/CollectionService.php';

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
        } catch (\Exception $e) {
            $response->getBody()->write($e->getCode().": ". $e->getMessage());
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        }
    }
}
