<?php

namespace service;

use dto\User;
use repository\UserRepository;

require_once __DIR__ . '/../repository/UserRepository.php';
require_once __DIR__ . '/../dto/User.php';

class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function select_users()
    {
        return $this->userRepository->select_user_list();
    }

    public function select_user(User $user)
    {
        return $this->userRepository->select_user($user);
    }
}
