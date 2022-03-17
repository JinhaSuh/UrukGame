<?php

namespace service;

use DateTime;
use dto\Boat;
use exception\GoldShortage;
use exception\InvalidError;
use exception\MaxGrade;
use exception\PearlShortage;
use exception\UpgradeFailure;
use exception\UserException;
use exception\InvalidRequestBody;
use repository\BoatRepository;
use repository\UserRepository;

require_once __DIR__ . '/../repository/BoatRepository.php';
require_once __DIR__ . '/../repository/UserRepository.php';
require_once __DIR__ . '/../exception/MaxGrade.php';
require_once __DIR__ . '/../exception/UserException.php';
require_once __DIR__ . '/../exception/InvalidRequestBody.php';
require_once __DIR__ . '/../exception/InvalidError.php';
require_once __DIR__ . '/../exception/UpgradeFailure.php';
require_once __DIR__ . '/../exception/GoldShortage.php';
require_once __DIR__ . '/../exception/PearlShortage.php';
require_once __DIR__ . '/../dto/Boat.php';

class BoatService
{
    private BoatRepository $boatRepository;
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->boatRepository = new BoatRepository();
        $this->userRepository = new UserRepository();
    }

    /**
     * @throws UserException|InvalidRequestBody
     */
    public function select_boat($user)
    {
        if (!isset($user["user_id"])) {
            throw new InvalidRequestBody();
        }

        return $this->boatRepository->select_boat($user["user_id"]);
    }

    /**
     * @throws InvalidRequestBody|UserException|UpgradeFailure|InvalidError|GoldShortage|PearlShortage|MaxGrade
     */
    public function upgrade_boat($user)
    {
        if (!isset($user["user_id"])) {
            throw new InvalidRequestBody();
        }

        //일단 내 보트 상태 가져오기
        $result = $this->boatRepository->select_boat($user["user_id"]);
        $curr_boat=new Boat();
        $curr_boat->set_boat_id($result["boat_id"]);
        $curr_boat->set_durability($result["durability"]);
        $curr_boat->set_fuel($result["fuel"]);
        $curr_boat->set_departure_time(new DateTime($result["departure_time"]));
        $curr_boat->set_map_id($result["map_id"]);

        //다음 등급 정보와 업그레이드에 필요한 재화 및 확률 가져오기
        $boat_upgrade_data = $this->boatRepository->select_boat_upgrade_data($curr_boat->boat_id + 1);
        //이미 최대 등급인 경우
        if (empty($boat_upgrade_data)) throw new MaxGrade();

        $curr_user = $this->userRepository->select_user($user);
        switch ($boat_upgrade_data["need_item_type_id"]) {
            case 1:
                $curr_user["gold"] -= $boat_upgrade_data["need_count"];
                if ($curr_user["gold"] < 0) throw new GoldShortage();
                break;
            case 2:
                $curr_user["pearl"] -= $boat_upgrade_data["need_count"];
                if ($curr_user["pearl"] < 0) throw new PearlShortage();
                break;
        }

        $updated_user = $this->userRepository->update_user($curr_user);

        $success_rate = $boat_upgrade_data["success_rate"];
        $ran_num = rand(1, 100);
        if ($ran_num <= $success_rate) {
            $curr_boat->boat_id += 1;
            return $this->boatRepository->update_boat($user["user_id"], $curr_boat);
        } else
            throw new UpgradeFailure();
    }
}
