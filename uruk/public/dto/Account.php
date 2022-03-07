<?php

namespace dto;

class Account
{
    private int $user_id;
    private string $player_id;
    private string $password;
    private string $nation;
    private string $language;

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
    public function get_player_id(): string
    {
        return $this->player_id;
    }

    /**
     * @param string $player_id
     */
    public function set_player_id(string $player_id): void
    {
        $this->player_id = $player_id;
    }

    /**
     * @return string
     */
    public function get_password(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function set_password(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function get_nation(): string
    {
        return $this->nation;
    }

    /**
     * @param string $nation
     */
    public function set_nation(string $nation): void
    {
        $this->nation = $nation;
    }

    /**
     * @return string
     */
    public function get_language(): string
    {
        return $this->language;
    }

    /**
     * @param string $language
     */
    public function set_language(string $language): void
    {
        $this->language = $language;
    }


}
