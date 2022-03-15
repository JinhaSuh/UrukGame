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
    public function buy_fatigue(User $user)
    {
        //유저 정보 가져오기
        $result = $this->userRepository->select_user($user);
        $selected_user = User::Deserialize($result);

        //레벨 기획 데이터 가져오기
        $current_level_data = $this->userRepository->select_level_data($selected_user->get_level());
        $maxFatigue = $current_level_data["max_fatigue"];

        //골드를 소비하고, 피로도를 증가
        $selected_user->set_fatigue($selected_user->get_fatigue() + 5);
        $selected_user->set_gold($selected_user->get_gold() - 100);
        if ($selected_user->fatigue >= $maxFatigue) throw new UserException("충전 시 최대 피로도를 초과합니다.", 555);
        if ($selected_user->gold < 0) throw new UserException("골드가 부족합니다.", 607);

        $updated_user = $this->userRepository->update_user($selected_user);
        return $updated_user;
    }
}
