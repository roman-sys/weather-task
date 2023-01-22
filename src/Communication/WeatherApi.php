<?php
namespace App\Communication;

interface WeatherApi
{

    public function getTemperature(): float;
}