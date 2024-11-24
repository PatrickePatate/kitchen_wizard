<?php

use Illuminate\Support\Str;

return [
    'city' => env('WEATHER_CITY', 'Strasbourg'),

    /**
     * ⚠️ WARNING
     * Longitude and latitude will be used to get wheather data.
     * City is only used for display purposes.
     */

    'latitude' => env('WEATHER_LATITUDE', 48.580033),
    'longitude' => env('WEATHER_LONGITUDE', 7.7442619),

    'cache_duration_in_minutes' => env('WEATHER_CACHE_DURATION_IN_MINUTES', 60),
];
