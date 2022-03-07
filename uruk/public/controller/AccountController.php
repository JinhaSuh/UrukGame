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
        $requestBody = $request->getParsedBody();
        $player_id = $requestBody["playerId"];
        $password = $requestBody["password"];

        $account = new Account();
        $account->set_player_id($player_id);
        $account->set_password($password);

        $result = $this->accountService->select_account($account);

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function signUp(Request $request, Response $response)
    {
        $data = $request->getParsedBody();
        $player_id = $data["playerId"];
        $password = $data["password"];
        $nation = $data["nation"];
        $language = $data["language"];

        $account = new Account();
        $account->set_player_id($player_id);
        $account->set_password($password);
        $account->set_nation($nation);
        $account->set_language($language);

        $result = $this->accountService->insert_account($account);

        //$response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }
}
