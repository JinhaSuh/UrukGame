<?php

namespace dto;

use JsonDeserializer;

require_once __DIR__ . '/../JsonDeserializer.php';

class User extends JsonDeserializer implements \JsonSerializable
{
    public int $user_id;
    public string $user_name;
    public int $level;
    public int $exp;
    public int $fatigue;
    public int $gold;
    public int $pearl;

    /**
     * @return int
     */
    public function get_user_id(): int
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     */
    public function set_user_id(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * @return string
     */
    public function get_username(): string
    {
        return $this->user_name;
    }

    /**
     * @param string $user_name
     */
    public function set_username(string $user_name): void
    {
        $this->user_name = $user_name;
    }

    /**
     * @return int
     */
    public function get_level(): int
    {
        return $this->level;
    }

    /**
     * @param int $level
     */
    public function set_level(int $level): void
    {
        $this->level = $level;
    }

    /**
     * @return int
     */
    public function get_exp(): int
    {
        return $this->exp;
    }

    /**
     * @param int $exp
     */
    public function set_exp(int $exp): void
    {
        $this->exp = $exp;
    }

    /**
     * @return int
     */
    public function get_fatigue(): int
    {
        return $this->fatigue;
    }

    /**
     * @param int $fatigue
     */
    public function set_fatigue(int $fatigue): void
    {
        $this->fatigue = $fatigue;
    }

    /**
     * @return int
     */
    public function get_gold(): int
    {
        return $this->gold;
    }

    /**
     * @param int $gold
     */
    public function set_gold(int $gold): void
    {
        $this->gold = $gold;
    }

    /**
     * @return int
     */
    public function get_pearl(): int
    {
        return $this->pearl;
    }

    /**
     * @param int $pearl
     */
    public function set_pearl(int $pearl): void
    {
        $this->pearl = $pearl;
    }

    public function jsonSerialize()
    {
        return [
            'user_id' => $this->user_id,
            'user_name' => $this->user_name,
            'level' => $this->level,
            'exp' => $this->exp,
            'fatigue' => $this->fatigue,
            'gold' => $this->gold,
            'pearl' => $this->pearl
        ];
    }
}
