<?php

namespace dto;

class User
{
    private int $user_id;
    private string $nickname;
    private int $level;
    private int $exp;
    private int $fatigue;
    private int $gold;
    private int $pearl;

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
    public function get_nickname(): string
    {
        return $this->nickname;
    }

    /**
     * @param string $nickname
     */
    public function set_nickname(string $nickname): void
    {
        $this->nickname = $nickname;
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
}
