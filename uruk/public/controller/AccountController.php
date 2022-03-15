<?php

namespace controller;

use exception\AccountException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use service\AccountService;
use dto\Account;

use Slim\App;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../dto/Account.php';
require_once __DIR__ . '/../service/AccountService.php';
require_once __DIR__ . '/../exception/AccountException.php';

class AccountController
{
    private AccountService $accountService;

    public function __construct()
    {
        $this->accountService = new AccountService();
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
        $input = $request->getParsedBody();
        $data = json_decode((string)json_encode($input), false);

        //필수 입력값을 입력받았는지 확인
        if (!isset($data->player_id) || !isset($data->password)) {
            $accountException = new AccountException("요청 형식이 잘못되었습니다.", 510);
            $response->getBody()->write($accountException->getMessage());
            return $response->withHeader('content-type', 'application/json')->withStatus(510);
        }

        try {
            $account = Account::Deserialize($data);
            $select_account = $this->accountService->select_account($account);
            $response->getBody()->write(json_encode($select_account));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(200);
        } catch (\PDOException|AccountException $e) {
            $response->getBody()->write($e->getMessage());
            return $response->withHeader('content-type', 'application/json')->withStatus(507);
        }
    }

    public function signUp(Request $request, Response $response)
    {
        $input = $request->getParsedBody();
        $data = json_decode((string)json_encode($input), false);

        //필수 입력값을 입력받았는지 확인
        if (!isset($data->player_id) || !isset($data->password)) {
            $accountException = new AccountException("요청 형식이 잘못되었습니다.", 510);
            $response->getBody()->write($accountException->getMessage());
            return $response->withHeader('content-type', 'application/json')->withStatus(510);
        }

        try {
            $account = Account::Deserialize($data);
            $new_account = $this->accountService->insert_account($account);
            $response->getBody()->write(json_encode($new_account));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(200);
        } catch (\PDOException|AccountException $e) {
            $response->getBody()->write($e->getMessage());
            return $response->withHeader('content-type', 'application/json')->withStatus(507);
        }
    }
}
