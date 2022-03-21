<?php

namespace App\dto;

use DateTime;

class Boat extends JsonDeserializer
{
    public int $boat_id;
    public int $durability;
    public int $fuel;
    public DateTime $departure_time;
    public int $map_id;

    /**
     * @return int
     */
    public function get_boat_id(): int
    {
        return $this->boat_id;
    }

    /**
     * @param int $boat_id
     */
    public function set_boat_id(int $boat_id): void
    {
        $this->boat_id = $boat_id;
    }

    /**
     * @return int
     */
    public function get_durability(): int
    {
        return $this->durability;
    }

    /**
     * @param int $durability
     */
    public function set_durability(int $durability): void
    {
        $this->durability = $durability;
    }

    /**
     * @return int
     */
    public function get_fuel(): int
    {
        return $this->fuel;
    }

    /**
     * @param int $fuel
     */
    public function set_fuel(int $fuel): void
    {
        $this->fuel = $fuel;
    }

    /**
     * @return DateTime
     */
    public function get_departure_time(): DateTime
    {
        return $this->departure_time;
    }

    /**
     * @param DateTime $departure_time
     */
    public function set_departure_time(DateTime $departure_time): void
    {
        $this->departure_time = $departure_time;
    }

    /**
     * @return int
     */
    public function get_map_id(): int
    {
        return $this->map_id;
    }

    /**
     * @param int $map_id
     */
    public function set_map_id(int $map_id): void
    {
        $this->map_id = $map_id;
    }
}
