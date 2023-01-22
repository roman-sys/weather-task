<?php
namespace App\Communication\Api;

use App\Communication\WeatherApi;
use App\Communication\CommonApi;

class OpenWeather extends CommonApi implements WeatherApi
{

    public function getUrl(array $params = []): string
    {
        $params = array_merge([
            'lat' => $this->getLatitude(),
            'lon' => $this->getLongitude(),
            'appid' => $this->containerBag->get('openweather_api_key'),
            'units' => $this->containerBag->get('openweather_units')
        ], $params);

        $url = $this->containerBag->get('openweather_baseurl') . '?' . http_build_query($params);

        return $url;
    }

    public function isValidData(): bool
    {
        $data = $this->getLoadData();
        return isset($data['main']['temp']);
    }

    public function getTemperature(): float
    {
        $data = $this->getLoadData();
        return $data['main']['temp'];
    }
}