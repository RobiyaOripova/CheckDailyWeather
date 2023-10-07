<?php

namespace App\Services;

use App\Mail\SendWeather;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class WeatherService
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
                    case 'weatherbit':
                        $data = $json['data'][0];
                        $measure = ['m/s', 'mm/hr', 'mb'];
                        $resData = $this->getResponseValues(
                            $data['app_temp'],
                            $data['pres'],
                            $data['precip'],
                            $data['wind_spd'],
                            $data['weather']['description'],
                            $data['timezone'],
                        );
                        break;
                    case 'weather-api';
                        $data = $json['current'];
                        $measure = ['mph', 'in', 'in'];
                        $resData = $this->getResponseValues(
                            $data['temp_c'],
                            $data['pressure_in'],
                            $data['precip_in'],
                            $data['wind_mph'],
                            $data['condition']['text'],
                            $json['location']['tz_id'],
                        );
                        break;
                    default:
                        $data = $json['current'];
                        $measure = ['kmph', 'mm', 'mb'];
                        $resData = $this->getResponseValues(
                            $data['temperature'],
                            $data['pressure'],
                            $data['precip'],
                            $data['wind_speed'],
                            $data['weather_descriptions'][0],
                            $json['location']['timezone_id'],
                        );
                }
                switch ($channel){
                    case 'telegram':
                        $txt = "<b>Timezone:</b> " . $resData[5] . "\n\n" .
                            "<b>Temperature:</b> " . $resData[0] . " C" . "\n\n" .
                            "<b>Pressure:</b> " . $resData[1] . " " . $measure[0] . "\n\n" .
                            "<b>Precip:</b> " . $resData[2] . " " . $measure[1] . "\n\n" .
                            "<b>Wind:</b> " . $resData[3] . " " . $measure[2] . "\n\n" .
                            "<b>Title:</b> " . $resData[4] . "\n\n";

                        $txt = urlencode($txt);
                        $url = "https://api.telegram.org/bot6144305316:AAF-OkUF8zRDSgKsAvNXxBvNYlwU4LCe5Lc/sendMessage?chat_id=$to&text=$txt" . "&parse_mode=html";
                        file_get_contents($url);
                        break;
                    case 'email':
                        Mail::to($to)->send(new SendWeather($resData, $measure));
                        break;
                    default:
                       echo "Timezone: " . $resData[5] . "\n\n" .
                            "Temperature: " . $resData[0] . " C" . "\n\n" .
                            "Pressure: " . $resData[1] . " " . $measure[0] . "\n\n" .
                            "Precip: " . $resData[2] . " " . $measure[1] . "\n\n" .
                            "Wind: " . $resData[3] . " " . $measure[2] . "\n\n" .
                            "Title: " . $resData[4] . "\n\n";
                }

            } else {
                print_r($response->body());
                print_r($response->json());
            }
        } catch (Exception $exception) {
            print_r($exception->getMessage());
        }
    }
    public function getResponseValues($temperature, $pressure, $precip, $wind, $title, $tz): array
    {
        $values = [];
        foreach (func_get_args() as $arg) {
            $values[] = $arg;
        }
        return $values;
    }

}