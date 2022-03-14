<?php

namespace service;

use exception\AccountException;
use repository\AccountRepository;
use dto\Account;

require_once __DIR__ . '/../repository/AccountRepository.php';
require_once __DIR__ . '/../dto/Account.php';

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
     * @throws AccountException
     */
    public function select_account(Account $account)
    {
        return $this->accountRepository->select_account($account);
    }

    /**
     * @throws AccountException
     */
    public function insert_account(Account $account): Account
    {
        return $this->accountRepository->insert_account($account);
    }
}
