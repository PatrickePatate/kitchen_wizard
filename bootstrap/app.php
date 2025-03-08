<?php

use App\Jobs\BuildDailyRecipeSelectionJob;
use App\Jobs\SendRecipesSuggestionsToUsersJob;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Sentry\Laravel\Integration;

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
        Integration::handles($exceptions);
    })
    ->withSchedule(function (\Illuminate\Console\Scheduling\Schedule $schedule) {
        $schedule->call(function () {
            BuildDailyRecipeSelectionJob::dispatchSync();
        })->dailyAt('00:00');

        $schedule->command('model:prune')->daily();

        $schedule->call(function () {
            SendRecipesSuggestionsToUsersJob::dispatchSync();
        })->dailyAt('08:30');

        if(config('app.backups.enabled', true)) {
            $schedule->command('backup:clean')->daily()->at('01:00');
            $schedule->command('backup:run')->daily()->at('01:30');
        }
    })->create();
