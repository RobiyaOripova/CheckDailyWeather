<?php

namespace App\Interfaces;

interface WeatherInterface
{
    public function dataHelper($args, $arg, $provider, $data, $data2);

    public function infoText($data, $data2, $args, $measure, $provider): array;
}