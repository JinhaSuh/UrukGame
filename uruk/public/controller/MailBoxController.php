<?php

namespace controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use service\MailBoxService;

use Slim\App;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../service/MailBoxService.php';

class MailBoxController
{
    private MailBoxService $mailBoxService;

    public function __construct()
    {
        $this->mailBoxService = new MailBoxService();
    }

    public function selectMailBox(Request $request, Response $response)
    {
        $input = $request->getParsedBody();

        try {
            $mailBox = $this->mailBoxService->select_mailBox($input);
            $response->getBody()->write(json_encode($mailBox));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(200);
        } catch (\Exception $e) {
            $response->getBody()->write($e->getCode().": ". $e->getMessage());
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        }
    }

    public function receiveMailBoxItem(Request $request, Response $response)
    {
        $input = $request->getParsedBody();

        try {
            $inventory = $this->mailBoxService->receive_item($input);
            $response->getBody()->write(json_encode($inventory));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(200);
        } catch (\Exception $e) {
            $response->getBody()->write($e->getCode().": ". $e->getMessage());
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        }
    }
}
