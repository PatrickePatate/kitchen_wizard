<?php

use App\Jobs\BuildDailyRecipeSelectionJob;
use App\Jobs\SendRecipesSuggestionsToUsersJob;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withSchedule(function (\Illuminate\Console\Scheduling\Schedule $schedule) {
        $schedule->call(function () {
            BuildDailyRecipeSelectionJob::dispatchSync();
        })->dailyAt('00:00');

        $schedule->command('model:prune')->daily();

        $schedule->call(function () {
            SendRecipesSuggestionsToUsersJob::dispatchSync();
        })->dailyAt('08:30');
    })->create();
