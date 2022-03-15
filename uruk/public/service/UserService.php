<?php

namespace service;

use exception\ExcessMaxFatigue;
use exception\GoldShortage;
use exception\InvalidError;
use exception\UserException;
use repository\UserRepository;

require_once __DIR__ . '/../repository/UserRepository.php';
require_once __DIR__ . '/../dto/User.php';
require_once __DIR__ . '/../exception/ExcessMaxFatigue.php';
require_once __DIR__ . '/../exception/GoldShortage.php';

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
    public function select_user($user)
    {
        if (!isset($user["user_id"])) {
            throw new InvalidRequestBodyException();
        }

        return $this->userRepository->select_user($user);
    }

    /**
     * @throws ExcessMaxFatigue
     * @throws GoldShortage
     * @throws UserException
     * @throws InvalidError
     */
    public function buy_fatigue($user)
    {
        if (!isset($user["user_id"])) {
            throw new InvalidRequestBodyException();
        }

        //유저 정보 가져오기
        $selected_user = $this->userRepository->select_user($user);

        //레벨 기획 데이터 가져오기
        $current_level_data = $this->userRepository->select_level_data($selected_user["level"]);
        $maxFatigue = $current_level_data["max_fatigue"];

        //골드를 소비하고, 피로도를 증가
        $selected_user["fatigue"] += 5;
        $selected_user["gold"] -= 100;
        if ($selected_user["fatigue"] >= $maxFatigue) throw new ExcessMaxFatigue();
        if ($selected_user["gold"] < 0) throw new GoldShortage();

        $updated_user = $this->userRepository->update_user($selected_user);
        return $updated_user;
    }
}
