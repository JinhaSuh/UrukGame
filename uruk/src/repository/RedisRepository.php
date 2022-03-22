<?php

namespace App\repository;

use Redis;

class RedisRepository
{
    public function add_user_gold(int $user_id, int $gold)
    {
        $redis = new Redis();
        $redis->connect('localhost', 6379);
        $key = "ranking";
        $updated_gold = $redis->zIncrBy($key, $gold, $user_id);
        return $updated_gold;
    }

    public function select_user_ranking(int $user_id)
    {
        $redis = new Redis();
        $redis->connect('localhost', 6379);
        $key = "ranking";
        $curr_rank = $redis->zRevRank($key, $user_id); //금액이 높을수록 높은 등수
        return $curr_rank;
    }

    public function select_user_gold(int $user_id)
    {
        $redis = new Redis();
        $redis->connect('localhost', 6379);
        $key = "ranking";
        $curr_gold = $redis->zScore($key, $user_id);
        return $curr_gold;
    }

}
