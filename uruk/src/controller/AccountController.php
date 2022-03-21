<?php

namespace App\controller;

use App\exception\MyException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\service\AccountService;

class AccountController
{
    private AccountService $accountService;

    public function __construct()
    {
        $this->accountService = new AccountService();
    }

    public function login(Request $request, Response $response)
    {
        $input = $request->getParsedBody();

        try {
            $select_account = $this->accountService->select_account($input);
            $response->getBody()->write(json_encode($select_account));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(200);
        } catch (MyException $e) {
            $response->getBody()->write(json_encode($e->jsonSerialize()));
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        }
    }

    public function signUp(Request $request, Response $response)
    {
        $input = $request->getParsedBody();

        try {
            $new_account = $this->accountService->insert_account($input);
            $response->getBody()->write(json_encode($new_account));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(200);
        } catch (MyException $e) {
            $response->getBody()->write(json_encode($e->jsonSerialize()));
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        }
    }
}
