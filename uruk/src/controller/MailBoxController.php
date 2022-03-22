<?php

namespace App\controller;

use App\exception\MyException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\service\MailBoxService;

class MailBoxController
{
    private MailBoxService $mailBoxService;

    public function __construct()
    {
        $this->mailBoxService = new MailBoxService();
    }

    public function getMailBox(Request $request, Response $response)
    {
        $input = $request->getParsedBody();

        try {
            $mailBox = $this->mailBoxService->select_mailBox($input);
            $response->getBody()->write(json_encode($mailBox));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(200);
        } catch (MyException $e) {
            $response->getBody()->write(json_encode($e->jsonSerialize()));
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        }
    }

    public function getMail(Request $request, Response $response)
    {
        $input = $request->getParsedBody();

        try {
            $inventory = $this->mailBoxService->receive_item($input);
            $response->getBody()->write(json_encode($inventory));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(200);
        } catch (MyException $e) {
            $response->getBody()->write(json_encode($e->jsonSerialize()));
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        }
    }
}
