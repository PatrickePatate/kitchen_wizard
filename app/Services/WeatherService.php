<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class WeatherService
{
    public static function getWeather(): array
    {
        return Cache::remember("weather", now()->addMinutes(config('weather.cache_duration_in_minutes')), function () {
            $endpoint = sprintf("https://api.open-meteo.com/v1/forecast?latitude=%f&longitude=%f&current=temperature_2m,wind_speed_10m,weather_code",
                config('weather.latitude'),
                config('weather.longitude')
            );

            return Http::get($endpoint)->json();
        });
    }

    public static function getTemperature(): string
    {
        $weather = self::getWeather();
        $temperature = $weather['current']['temperature_2m'];

        return sprintf("%d°C", $temperature);
    }

    public static function getCity(): string
    {
        return config('weather.city');
    }

    public static function getIcon(): string
    {
        $weather = self::getWeather();
        $code = $weather['current']['weather_code'];

        switch($code) {
            case 0:
                return self::fetchIconFromName('clear');
                break;
            case 1:
                return self::fetchIconFromName('mostly-clear');
                break;
            case 2:
                return self::fetchIconFromName('partly-cloudy');
                break;
            case 3:
                return self::fetchIconFromName('overcast');
                break;

            case 45:
                return self::fetchIconFromName('fog');
                break;
            case 48:
                return self::fetchIconFromName('rime-fog');
                break;

            case 51:
                return self::fetchIconFromName('light-drizzle');
                break;
            case 53:
                return self::fetchIconFromName('moderate-drizzle');
                break;
            case 55:
                return self::fetchIconFromName('dense-drizzle');
                break;

            case 80:
            case 61:
                return self::fetchIconFromName('light-rain');
                break;
            case 81:
            case 63:
                return self::fetchIconFromName('moderate-rain');
                break;
            case 82:
            case 65:
                return self::fetchIconFromName('heavy-rain');
                break;

            case 56:
                return self::fetchIconFromName('light-freezing-drizzle');
                break;
            case 57:
                return self::fetchIconFromName('dense-freezing-drizzle');
                break;

            case 66:
                return self::fetchIconFromName('light-freezing-rain');
                break;
            case 67:
                return self::fetchIconFromName('heavy-freezing-rain');
                break;

            case 77:
                return self::fetchIconFromName('snowflake');
                break;

            case 85:
            case 71:
                return self::fetchIconFromName('slight-snowfall');
                break;
            case 73:
                return self::fetchIconFromName('moderate-snowfall');
                break;
            case 86:
            case 75:
                return self::fetchIconFromName('heavy-snowfall');
                break;

            case 95:
                return self::fetchIconFromName('thunderstorm');
                break;
            case 96:
            case 99:
                return self::fetchIconFromName('thunderstorm-with-hail');
                break;
            default:
                return self::fetchIconFromName('clear');
        }
    }

    private static function fetchIconFromName($name): string
    {
        return asset("weather/icons/airy/$name.png");
    }
}
