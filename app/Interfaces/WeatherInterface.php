<?php

namespace App\Interfaces;

interface WeatherInterface
{
    public function dataHelper($jsonData, $data, $provider, $firstData, $secondData);

    public function infoText($firstData, $secondData, $jsonData, $units, $provider): array;
}