<?php

namespace App\service;

use App\exception\InvalidRequestBody;
use App\repository\RankingRepository;
use App\repository\RedisRepository;

class RankingService
{
    private RankingRepository $rankingRepository;
    private RedisRepository $redisRepository;

    public function __construct()
    {
        $this->rankingRepository = new RankingRepository();
        $this->redisRepository = new RedisRepository();
    }

    /**
     * @throws InvalidRequestBody
     */
    public function select_ranking($input)
    {
        //필수 입력값을 입력받았는지 확인
        if (!isset($input["user_id"])) {
            throw new InvalidRequestBody();
        }

        $user_rank = $this->redisRepository->select_user_ranking($input["user_id"]);
        $user_gold = $this->redisRepository->select_user_gold($input["user_id"]);
        return [
            "user_id" => $input["user_id"],
            "gold_sum" => $user_gold,
            "ranking" => $user_rank + 1
        ];
/*
        $ranking = $this->rankingRepository->select_ranking();
        for ($i = 0; $i < count(ranking); $i++) {
            if ($ranking[$i]["user_id"] == $input["user_id"]) {
                return [
                    "user_id" => $ranking[$i]["user_id"],
                    "week_date" => $ranking[$i]["week_date"],
                    "gold_sum" => $ranking[$i]["gold_sum"],
                    "ranking" => $i + 1
                ];
            }
        }

        throw new UnknownUser();
*/
    }

}
