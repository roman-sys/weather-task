<?php
namespace App\Communication\Api;

use App\Communication\WeatherApi;
use App\Communication\HttpClientApi;
use Exception;

class WeatherApiRepository
{
    use HttpClientApi;
    
    private array $weatherApiConfig = [];

    private OpenWeatherGeo $openWeatherGeo;

    public function __construct(OpenWeatherGeo $openWeatherGeo, array $weatherApiConfig = [])
    {
        $this->weatherApiConfig = $weatherApiConfig;
        $this->openWeatherGeo = $openWeatherGeo;
        $this->deleteBadConfiguration();
    }

    public function configApi(string $spot, string $country): self
    {
        $this->openWeatherGeo->setSpot($spot)->setCountry($country);

        $openWeatherGeoArray = $this->sendMultiplyFor([
            $this->openWeatherGeo
        ]);

        if (! $this->openWeatherGeo->isValidData()) {
            throw new Exception('Location could not be found or service not available');
        }

        $this->openWeatherGeo = reset($openWeatherGeoArray);

        foreach ($this->weatherApiConfig as $weatherApi) {
            $weatherApi->setLatitude($this->openWeatherGeo->getLatitude())
                ->setLongitude($this->openWeatherGeo->getLongitude());
        }

        return $this;
    }

    public function calculateAverageTemperature(): ?float
    {
        $this->weatherApiConfig = $this->sendMultiplyFor($this->weatherApiConfig);
        
        $temperature = 0;
        foreach ($this->weatherApiConfig as $weatherApi) {
            $temperature += $weatherApi->getTemperature();
        }

        $result = null;
        if (count($this->weatherApiConfig) > 0) {
            $result = round($temperature / count($this->weatherApiConfig), 2);
        }

        return $result;
    }

    private function deleteBadConfiguration(): void
    {
        foreach ($this->weatherApiConfig as $key => $weatherApi) {
            if (! $weatherApi instanceof WeatherApi) {
                unset($this->weatherApiConfig[$key]);
            }
        }
    }
}