<?php

namespace App\Services;

use App\Mail\SendWeather;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class WeatherService extends BaseService
{
    public function sendWeatherInfo($provider, $city, $to, $channel)
    {
        $weatherbit = config('system.weatherbit_url') . "&city=" . $city;
        $weatherApi = config('system.weather_api_url') . "&q=" . $city;
        $weatherStack = config('system.weather_stack_url') . "&query=" . $city;

        $url = match ($provider) {
            'weatherbit' => $weatherbit,
            'weather-api' => $weatherApi,
            default => $weatherStack,
        };

        $response = Http::get($url);
        try {
            if ($response->status() == 200) {

                $json = $response->json();

                switch ($provider) {
                    case self::WEATHER_BIT:
                        $data = $json['data'][0];
                        $measure = ["c", 'm/s', 'mm/hr', 'mb'];
                        $args = ['app_temp', 'pres', 'precip', 'wind_spd', ['weather', 'description'], 'timezone'];
                        $text = $this->infoText($data, null, $args, $measure, $provider)['text'];
                        $emailData = $this->infoText($data, null, $args, $measure, $provider)['values'];
                        break;
                    case self::WEATHER_API:
                        $data = $json['current'];
                        $data2 = $json;
                        $measure = ["c", 'mph', 'in', 'in'];
                        $args = ['temp_c', 'pressure_in', 'precip_in', 'wind_mph', ['condition', 'text'], ['location', 'tz_id']];
                        $text = $this->infoText($data, $data2, $args, $measure, $provider)['text'];
                        $emailData = $this->infoText($data, $data2, $args, $measure, $provider)['values'];
                        break;
                    default:
                        $data = $json['current'];
                        $data2 = $json;
                        $measure = ["c", 'kmph', 'mm', 'mb'];
                        $args = ['temperature', 'pressure', 'precip', 'wind_speed', ['weather_descriptions', 0], ['location', 'timezone_id']];
                        $text = $this->infoText($data, $data2, $args, $measure, $provider)['text'];
                        $emailData = $this->infoText($data, $data2, $args, $measure, $provider)['values'];
                }
                switch ($channel) {
                    case self::TELEGRAM:
                        $txt = $text;
                        $txt = urlencode($txt);
                        $url = config('system.telegram_bot_url') . "&chat_id=$to&text=$txt";
                        file_get_contents($url);
                        break;
                    case self::EMAIL:
                        Mail::to($to)->send(new SendWeather($emailData, $measure));
                        break;
                    default:
                        echo $text;
                }

            } else {
                print_r($response->body());
                print_r($response->json());
            }
        } catch (Exception $exception) {
            print_r($exception->getMessage());
        }
    }


}