<?php

namespace controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use service\AccountService;
use dto\Account;

use Slim\App;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../dto/Account.php';
require_once __DIR__ . '/../service/AccountService.php';

class AccountController
{
    private AccountService $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function selectAccounts(Request $request, Response $response)
    {
        $accounts = $this->accountService->select_accounts();

        $response->getBody()->write(json_encode($accounts));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function login(Request $request, Response $response)
    {
        $data = $request->getParsedBody();
        $account = Account::Deserialize($data);

        $result = $this->accountService->select_account($account);

        if ($result > 0) $response->getBody()->write(json_encode($result));
        else {
            $response->getBody()->write(json_encode(array('errorCode' => '507', 'message' => '알 수 없는 HiveID입니다.')));
            return $response->withHeader('content-type', 'application/json')->withStatus(507);
        }
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function signUp(Request $request, Response $response)
    {
        $data = $request->getParsedBody();
        $account = Account::Deserialize($data);

        $result = $this->accountService->insert_account($account);

        //결과를 User 객체로
        $user = Account::Deserialize($result);

        if (!$result)
            $response->getBody()->write(json_encode(success));
        else {
            $response->getBody()->write(json_encode(array('errorCode' => '507', 'message' => '알 수 없는 HiveID입니다.')));
            return $response->withHeader('content-type', 'application/json')->withStatus(507);
        }
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }
}
