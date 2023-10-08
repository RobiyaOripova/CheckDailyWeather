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
    public $json;


    public function dataHelper($args, $arg, $provider, $data, $data2)
    {
        if ($arg == end($args)) {
            if (($provider == self::WEATHER_API || $provider == self::WEATHER_STACK)) {
                $this->json = $data2;
            }
        } else {
            $this->json = $data;
        }
        return $this->json;
    }

    public function infoText($data, $data2, $args, $measure, $provider): array
    {
        $titles = ['Temperature', 'Pressure', 'Precip', 'Wind', 'Title', 'Timezone'];
        $text = '';
        $count = 0;
        $count2 = 0;
        $values = [];
        foreach ($args as $arg) {
            $json = $this->dataHelper($args, $arg, $provider, $data, $data2);
            $title = json_encode($titles[$count]);
            if ($count2 !== 4) {
                $m = $measure[$count2];
                $count2++;
            } else {
                $m = "";
            }
            if ((is_array($arg))) {
                $text .= $title . ": " . json_encode($json[$arg[0]][$arg[1]]) . " " . $m . "\n\n";
                $values[] = $json[$arg[0]][$arg[1]];
            } else {
                $text .= $title . ": " . json_encode($json[$arg]) . " " . $m . "\n\n";
                $values[] = $json[$arg];
            }

            $count++;
        }
        return [
            'text' => $text,
            'values' => $values
        ];

    }

}