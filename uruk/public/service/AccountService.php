<?php

namespace service;

use exception\InvalidRequestBodyException;
use exception\UnknownHiveID;
use repository\AccountRepository;

require_once __DIR__ . '/../repository/AccountRepository.php';

class AccountService
{
    private AccountRepository $accountRepository;

    public function __construct()
    {
        $this->accountRepository = new AccountRepository();
    }

    public function select_accounts()
    {
        return $this->accountRepository->select_account_list();
    }

    /**
     * @throws UnknownHiveID
     * @throws InvalidRequestBodyException
     */
    public function select_account($account)
    {
        //필수 입력값을 입력받았는지 확인
        if (!isset($account["player_id"]) || !isset($account["password"])) {
            throw new InvalidRequestBodyException();
        }

        return $this->accountRepository->select_account($account);
    }

    /**
     * @throws InvalidRequestBodyException|UnknownHiveID
     */
    public function insert_account($account)
    {
        //필수 입력값을 입력받았는지 확인
        if (!isset($account["player_id"]) || !isset($account["password"])) {
            throw new InvalidRequestBodyException();
        }

        return $this->accountRepository->insert_account($account);
    }
}
