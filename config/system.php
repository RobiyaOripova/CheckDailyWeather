<?php

return [
    "weatherbit_url" => "https://api.weatherbit.io/v2.0/current?key=" . env('WEATHERBIT_KEY'),
    "weather_api_url" => "http://api.weatherapi.com/v1/current.json?key=" . env('WEATHER_API_KEY'),
    "weather_stack_url" => "http://api.weatherstack.com/current?access_key=" . env('WEATHER_STACK_KEY'),
    "telegram_bot_url"=>"https://api.telegram.org/bot6144305316:AAF-OkUF8zRDSgKsAvNXxBvNYlwU4LCe5Lc/sendMessage?parse_mode=html"
];
