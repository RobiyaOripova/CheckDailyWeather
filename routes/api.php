<?php

use App\Services\WeatherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/', function (Request $request) {

 return ( (new  WeatherService())->sendWeatherInfo("weatherbit", "Tashkent", "email", 'telegram'));
  //return ( (new  WeatherService())->sendWeatherInfo("weather-api", "Tashkent", "123", 'telegram'));
// return ( (new  WeatherService())->sendWeatherInfo("weather-stack", "Tashkent", "email", 'telegram'));

});