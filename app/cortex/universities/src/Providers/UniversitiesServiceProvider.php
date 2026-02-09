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
        // $routesPath = __DIR__.'/../../../routes/adminarea.php';
        // $routesPath = __DIR__.'/../../../routes/adminarea.php';

        dd(realpath(__DIR__ ));
        // $this->loadRoutesFrom($routesPath);
        $this->loadRoutesFrom(realpath(__DIR__ . '/../../routes/adminarea.php'));
        // $this->loadViewsFrom(__DIR__.'/../../../resources/views', 'cortex_universities');
        // $this->loadViewsFrom(__DIR__.'/../../../resources/views', 'cortex/universities');

        $this->loadViewsFrom(realpath(__DIR__ . '/../../resources/views'), 'cortex/universities');
    }
}
