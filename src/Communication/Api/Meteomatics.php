<?php
namespace App\Communication\Api;

use App\Communication\WeatherApi;
use App\Communication\CommonApi;

class Meteomatics extends CommonApi implements WeatherApi
{

    public function getUrl(array $params = []): string
    {
        $url = $this->containerBag->get('meteomatics_baseurl') . '/';
        $url .= $this->containerBag->get('meteomatics_time') . '/';
        $url .= $this->containerBag->get('meteomatics_apiparameter') . '/';
        $url .= $this->getLatitude() . ',' . $this->getLongitude() . '/';
        $url .= $this->containerBag->get('meteomatics_format');
        return $url;
    }

    public function getOptions(): array
    {
        return [
            'auth' => [
                $this->containerBag->get('meteomatics_login'),
                $this->containerBag->get('meteomatics_password')
            ]
        ];
    }

    public function isValidData(): bool
    {
        $data = $this->getLoadData();
        return isset($data['data'][0]['coordinates'][0]['dates'][0]['value']);
    }

    public function getTemperature(): float
    {
        $data = $this->getLoadData();
        return $data['data'][0]['coordinates'][0]['dates'][0]['value'];
    }
}