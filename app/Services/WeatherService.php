<?php

namespace App\Services;

use App\Mail\SendWeather;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class WeatherService extends BaseService
{

    public string $provider;
    public string $city;
    public $to;
    public ?string $channel;
    public $data2;

    public function __construct($provider, $city, $to, $channel)
    {
        $this->provider = $provider;
        $this->city = $city;
        $this->to = $to;
        $this->channel = $channel;
    }

    public function sendWeatherInfo(): void
    {
        $weatherbit = config('system.weatherbit_url') . "&city=" . $this->city;
        $weatherApi = config('system.weather_api_url') . "&q=" . $this->city;
        $weatherStack = config('system.weather_stack_url') . "&query=" . $this->city;

        $url = match ($this->provider) {
            self::WEATHER_BIT => $weatherbit,
            self::WEATHER_API => $weatherApi,
            default => $weatherStack,
        };

        $response = Http::get($url);
        try {
            if ($response->status() == 200) {

                $json = $response->json();

                switch ($this->provider) {
                    case self::WEATHER_BIT:
                        $data = $json['data'][0];
                        $this->data2 = null;
                        $measure = ["c", 'm/s', 'mm/hr', 'mb'];
                        $args = ['app_temp', 'pres', 'precip', 'wind_spd', ['weather', 'description'], 'timezone'];
                        break;
                    case self::WEATHER_API:
                        $data = $json['current'];
                        $this->data2 = $json;
                        $measure = ["c", 'mph', 'in', 'in'];
                        $args = ['temp_c', 'pressure_in', 'precip_in', 'wind_mph', ['condition', 'text'], ['location', 'tz_id']];
                        break;
                    default:
                        $data = $json['current'];
                        $this->data2 = $json;
                        $measure = ["c", 'kmph', 'mm', 'mb'];
                        $args = ['temperature', 'pressure', 'precip', 'wind_speed', ['weather_descriptions', 0], ['location', 'timezone_id']];
                }

                $infoText = $this->infoText($data, $this->data2, $args, $measure, $this->provider);
                $text = $infoText['text'];
                $emailData = $infoText['values'];

                switch ($this->channel) {
                    case self::TELEGRAM:
                        $txt = $text;
                        $txt = urlencode($txt);
                        $url = config('system.telegram_bot_url') . "&chat_id=$this->to&text=$txt";
                        file_get_contents($url);
                        break;
                    case self::EMAIL:
                        Mail::to($this->to)->send(new SendWeather($emailData, $measure));
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