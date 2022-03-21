<?php

namespace App\service;

use App\exception\InvalidRequestBody;
use App\exception\UnknownUser;
use App\repository\RankingRepository;

class RankingService
{
    private RankingRepository $rankingRepository;

    public function __construct()
    {
        $this->rankingRepository = new RankingRepository();
    }

    /**
     * @throws UnknownUser|InvalidRequestBody
     */
    public function select_ranking($input)
    {
        //필수 입력값을 입력받았는지 확인
        if (!isset($input["user_id"])) {
            throw new InvalidRequestBody();
        }

        //TODO : 주마다 리셋

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
    }

}
