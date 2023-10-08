<?php

namespace App\Console\Commands;

use App\Services\BaseService;
use App\Services\WeatherService;
use Illuminate\Console\Command;
use function Laravel\Prompts\select;

class Weather extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weather  {city} {--email=} {--telegram=} ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Daily weather';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $provider = select(
            label: 'Select a provider ',
            options: [
                BaseService::WEATHER_BIT => BaseService::WEATHER_BIT,
                BaseService::WEATHER_API => BaseService::WEATHER_API,
                BaseService::WEATHER_STACK => BaseService::WEATHER_STACK
            ],
            default: BaseService::WEATHER_BIT
        );
        $city = $this->argument('city');
        $email = $this->option('email');
        $telegram = $this->option('telegram');

        if (!empty($email)) {
            (new  WeatherService($provider, $city, $email, 'email'))->sendWeatherInfo();
        } elseif (!empty($telegram)) {
            (new  WeatherService($provider, $city, $telegram, 'telegram'))->sendWeatherInfo();
        } else {
            (new  WeatherService($provider, $city, null, null))->sendWeatherInfo();
        }

    }
}
