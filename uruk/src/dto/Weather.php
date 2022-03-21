<?php

namespace App\dto;

class Weather extends JsonDeserializer
{
    public Temperature $temperature;
    public Tide $tide;
    public Wind $wind;

    /**
     * @return Temperature
     */
    public function get_temperature(): Temperature
    {
        return $this->temperature;
    }

    /**
     * @param Temperature $temperature
     */
    public function set_temperature(Temperature $temperature): void
    {
        $this->temperature = $temperature;
    }

    /**
     * @return Tide
     */
    public function get_tide(): Tide
    {
        return $this->tide;
    }

    /**
     * @param Tide $tide
     */
    public function set_tide(Tide $tide): void
    {
        $this->tide = $tide;
    }

    /**
     * @return Wind
     */
    public function get_wind(): Wind
    {
        return $this->wind;
    }

    /**
     * @param Wind $wind
     */
    public function set_wind(Wind $wind): void
    {
        $this->wind = $wind;
    }
}
