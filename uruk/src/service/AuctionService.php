<?php

namespace App\service;

use App\exception\InvalidError;
use App\exception\InvalidRequestBody;
use App\exception\UnknownFish;
use App\exception\UnknownUser;
use App\repository\AuctionRepository;
use App\repository\FishingRepository;
use App\repository\RankingRepository;
use App\repository\WaterTankRepository;
use App\repository\UserRepository;

class AuctionService
{
    private AuctionRepository $auctionRepository;
    private WaterTankRepository $waterTankRepository;
    private UserRepository $userRepository;
    private FishingRepository $fishingRepository;
    private RankingRepository $rankingRepository;

    public function __construct()
    {
        $this->auctionRepository = new AuctionRepository();
        $this->waterTankRepository = new WaterTankRepository();
        $this->userRepository = new UserRepository();
        $this->fishingRepository = new FishingRepository();
        $this->rankingRepository = new RankingRepository();
    }

    /**
     * @throws InvalidRequestBody|UnknownUser|UnknownFish|InvalidError
     */
    public function select_auction($user)
    {
        if (!isset($user["user_id"])) {
            throw new InvalidRequestBody();
        }

        $curr_user = $this->userRepository->select_user($user);
        //유저 레벨 - 경매 버프 확률
        $user_level_data = $this->userRepository->select_level_data($curr_user["level"]);
        $buff_auction_sell_rate = $user_level_data["buff_auction_sell_rate"];

        //수조에 있는 모든 물고기
        $tank_fish_list = $this->waterTankRepository->select_water_tank($user["user_id"]);

        //tank_id로 경매 db조회
        for ($i = 0; $i < count($tank_fish_list); $i++) {
            $auction_fish = $this->auctionRepository->select_auction_fish($user["user_id"], $tank_fish_list[$i]["tank_id"]);
            $fish_data = $this->fishingRepository->select_fish_data($tank_fish_list[$i]["fish_id"]);

            $fish_auction_data = $this->auctionRepository->select_auction_data($tank_fish_list[$i]["fish_id"]);
            $rand_sell_rate = rand($fish_auction_data["min_sell_rate"], $fish_auction_data["max_sell_rate"]);

            $price = $fish_data["rarity"] * $tank_fish_list[$i]["length"] * $rand_sell_rate / 100 * $buff_auction_sell_rate / 100;

            if (empty($auction_fish))
                $this->auctionRepository->insert_auction_fish($user["user_id"], $tank_fish_list[$i]["tank_id"], $price);
        }

        $auction = $this->auctionRepository->select_auction($user["user_id"]);
        if (empty($auction)) throw new UnknownUser();
        else return $auction;
    }

    /**
     * @throws InvalidRequestBody|UnknownFish|UnknownUser|InvalidError|UnknownFish
     */
    public function sell_fish($input)
    {
        if (!isset($input["user_id"]) || !isset($input["auc_id"])) {
            throw new InvalidRequestBody();
        }

        //수조에 있는 모든 물고기
        $tank_fish_list = $this->waterTankRepository->select_water_tank($input["user_id"]);
        $auction_fish = $this->auctionRepository->select_auction_with_aucId($input["user_id"], $input["auc_id"]);

        //골드 증가
        $curr_user = $this->userRepository->select_user($input);
        $curr_user["gold"] += $auction_fish["price"];
        $curr_user["gold"] = strval($auction_fish["price"]);
        $updated_user = $this->userRepository->update_user($curr_user);

        //경매 목록에서 삭제
        $updated_auction = $this->auctionRepository->delete_auction_fish($input["user_id"], $input["auc_id"]);

        //수조 목록에서 삭제
        $updated_tank_fish_list = $this->waterTankRepository->delete_water_tank_fish($input["user_id"], $auction_fish["tank_id"]);

        //누적 판매 금액에 추가
        $user_ranking = $this->rankingRepository->select_user_ranking($input["user_id"]);
        if (empty($user_ranking)) $this->rankingRepository->insert_ranking($input["user_id"], date("Y-m-d H:i:s"), $auction_fish["price"]);
        else $this->rankingRepository->update_ranking($input["user_id"], date("Y-m-d H:i:s"), $user_ranking["gold_sum"] + $auction_fish["price"]);

        return $this->auctionRepository->select_auction($input["user_id"]);
    }
}
