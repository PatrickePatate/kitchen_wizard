<?php

namespace App\Providers;

use App\Sharp\SharpKitchenMenu;
use App\UserGroupEnum;
use Code16\Sharp\Config\SharpConfigBuilder;
use Code16\Sharp\SharpAppServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class SharpConfigProvider extends SharpAppServiceProvider
{

    protected function configureSharp(SharpConfigBuilder $config): void
    {
        $config
            ->setName(config('app.name'))
            ->setCustomUrlSegment('admin')
            ->displaySharpVersionInTitle(false)
            ->setSharpMenu(SharpKitchenMenu::class)
            ->configureUploads(keepOriginalImageOnTransform: true)
            ->discoverEntities()
            ->setThemeColor('#0047AB')
            ->setThemeLogo("/sharp-assets/logo.svg")
            ->enableLoginRateLimiting()
            ->suggestRememberMeOnLoginForm()
            ->enableImpersonation()
            ->setUserDisplayAttribute('name');
    }

    protected function declareAccessGate(): void
    {
        Gate::define('viewSharp', function ($user) {
            return $user->group == UserGroupEnum::ADMIN;
        });
    }
}
