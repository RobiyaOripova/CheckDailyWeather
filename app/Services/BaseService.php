<?php

namespace App\Services;

use App\Interfaces\WeatherInterface;

class BaseService implements WeatherInterface
{
    const WEATHER_BIT = 'weatherbit';
    const WEATHER_API = 'weather-api';
    const WEATHER_STACK = 'weather-stack';
    const TELEGRAM = 'telegram';
    const EMAIL = 'email';
    public $info;


    public function dataHelper($jsonData, $data, $provider, $firstData, $secondData)
    {
        if ($data == end($jsonData)) {
            if (($provider == self::WEATHER_API || $provider == self::WEATHER_STACK)) {
                $this->info = $secondData;
            }
        } else {
            $this->info = $firstData;
        }
        return $this->info;
    }

    public function infoText($firstData, $secondData, $jsonData, $units, $provider): array
    {
        $titles = ['Temperature', 'Pressure', 'Precip', 'Wind', 'Title', 'Timezone'];
        $text = '';
        $titleCount = 0;
        $unitCount = 0;
        $collectData = [];
        foreach ($jsonData as $data) {
            $helper = $this->dataHelper($jsonData, $data, $provider, $firstData, $secondData);
            $title = json_encode($titles[$titleCount]);
            if ($unitCount !== 4) {
                $unit = $units[$unitCount];
                $unitCount++;
            } else {
                $unit = "";
            }
            if ((is_array($data))) {
                $text .= $title . ": " . json_encode($helper[$data[0]][$data[1]]) . " " . $unit . "\n\n";
                $collectData[] = $helper[$data[0]][$data[1]];
            } else {
                $text .= $title . ": " . json_encode($helper[$data]) . " " . $unit . "\n\n";
                $collectData[] = $helper[$data];
            }

            $titleCount++;
        }
        return [
            'text' => $text,
            'values' => $collectData
        ];

    }

}