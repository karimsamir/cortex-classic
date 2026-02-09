<?php

namespace App\Cortex\Universities\Providers;

use Illuminate\Support\ServiceProvider;

class UniversitiesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $routesPath = __DIR__.'/../../../routes/adminarea.php';

        $this->loadRoutesFrom($routesPath);
        $this->loadViewsFrom(__DIR__.'/../../../resources/views', 'cortex_universities');

    }
}
