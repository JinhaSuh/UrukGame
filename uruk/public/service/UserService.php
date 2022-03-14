<?php

namespace service;

use dto\User;
use exception\UserException;
use repository\UserRepository;

require_once __DIR__ . '/../repository/UserRepository.php';
require_once __DIR__ . '/../dto/User.php';

class UserService
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function select_users()
    {
        return $this->userRepository->select_user_list();
    }

    /**
     * @throws UserException
     */
    public function select_user(User $user)
    {
        return $this->userRepository->select_user($user);
    }

    /**
     * @throws UserException
     */
    public function update_user(User $user)
    {
        return $this->userRepository->update_user($user);
    }

    /**
     * @throws UserException
     */
    public function select_level_data(int $level)
    {
        return $this->userRepository->select_level_data($level);
    }
}
