<?php

namespace service;

use dto\Weather;
use dto\Temperature;
use dto\Wind;
use dto\Tide;
use repository\WeatherRepository;

require_once __DIR__ . '/../dto/Weather.php';
require_once __DIR__ . '/../dto/Wind.php';
require_once __DIR__ . '/../dto/Temperature.php';
require_once __DIR__ . '/../dto/Tide.php';
require_once __DIR__ . '/../repository/WeatherRepository.php';

class WeatherService
{
    private WeatherRepository $weatherRepository;

    public function __construct()
    {
        $this->weatherRepository = new WeatherRepository();
    }

    public function select_weather_data()
    {
        $wind_data = $this->weatherRepository->select_wind_data();
        $temperature_data = $this->weatherRepository->select_temperature_data();
        $tide_data = $this->weatherRepository->select_tide_data();

        $weather = new Weather();
        $wind = Wind::Deserialize($wind_data);
        $temperature = Temperature::Deserialize($temperature_data);
        $tide = Tide::Deserialize($tide_data);

        $weather->set_wind($wind);
        $weather->set_temperature($temperature);
        $weather->set_tide($tide);

        return $weather;
    }
}
