<?php

namespace service;

use repository\AccountRepository;
use dto\Account;

require_once __DIR__ . '/../repository/AccountRepository.php';
require_once __DIR__ . '/../dto/Account.php';

class AccountService
{
    private AccountRepository $accountRepository;

    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function select_accounts()
    {
        return $this->accountRepository->select_account_list();
    }

    public function select_account(Account $account)
    {
        return $this->accountRepository->select_account($account);
    }

    public function insert_account(Account $account)
    {
        return $this->accountRepository->insert_account($account);
    }
}
