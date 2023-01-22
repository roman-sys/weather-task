<?php
namespace App\Communication\Api;

use App\Communication\CommonApi;
use Exception;

class OpenWeatherGeo extends CommonApi
{

    private string $spot;

    private string $country;

    public function setSpot(string $spot): self
    {
        $this->spot = $spot;
        return $this;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;
        return $this;
    }

    public function getUrl(array $params = []): string
    {
        $params = array_merge([
            'q' => $this->spot . ',' . $this->country,
            'limit' => 1,
            'appid' => $this->containerBag->get('openweather_api_key')
        ], $params);

        $url = $this->containerBag->get('openweather_basegeourl') . '?' . http_build_query($params);
        return $url;
    }

    public function isValidData(): bool
    {
        $data = $this->getLoadData();
        return (isset($data[0]['lat']) and isset($data[0]['lon']));
    }

    public function getLatitude(): float
    {
        $data = $this->getLoadData();
        return $data[0]['lat'];
    }

    public function getLongitude(): float
    {
        $data = $this->getLoadData();
        return $data[0]['lon'];
    }
}