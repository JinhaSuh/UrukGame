<?php

namespace App\service;

use App\exception\ExcessMaxFatigue;
use App\exception\GoldShortage;
use App\exception\InvalidError;
use App\exception\UnknownUser;
use App\exception\InvalidRequestBody;
use App\repository\ScribeRepository;
use App\repository\UserRepository;

class UserService
{
    private UserRepository $userRepository;
    private ScribeRepository $scribeRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->scribeRepository = new ScribeRepository();
    }

    /**
     * @throws UnknownUser|InvalidRequestBody
     */
    public function select_user($user)
    {
        if (!isset($user["user_id"])) {
            throw new InvalidRequestBody();
        }

        return $this->userRepository->select_user($user);
    }

    /**
     * @throws ExcessMaxFatigue|GoldShortage|UnknownUser|InvalidError|InvalidRequestBody
     */
    public function buy_fatigue($user)
    {
        if (!isset($user["user_id"])) {
            throw new InvalidRequestBody();
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

        //scribe - asset
        $this->scribeRepository->AssetLog($updated_user, 1, 100 * (-1), "buy_fatigue");

        return $updated_user;
    }
}
