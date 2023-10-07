<?php

namespace App\Console\Commands;

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
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $provider = select(
            label: 'Select a provider ',
            options: [
                'weatherbit' => 'weatherbit',
                'weather-api' => 'weather-api',
                'weather-stack' => 'weather-stack'
            ],
            default: 'weatherbit'
        );
        $city = $this->argument('city');
        $email = $this->option('email');
        $telegram = $this->option('telegram');

        if (!empty($email)) {
            (new  WeatherService())->sendWeatherInfo($provider, $city, $email, 'email');
        } elseif (!empty($telegram)) {
            (new  WeatherService())->sendWeatherInfo($provider, $city, $telegram, 'telegram');
        } else {
            (new  WeatherService())->sendWeatherInfo($provider, $city, null, null);
        }

    }
}
