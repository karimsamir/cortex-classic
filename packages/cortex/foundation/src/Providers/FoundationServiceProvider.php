<?php

declare(strict_types=1);

namespace Cortex\Foundation\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Cortex Foundation Service Provider
 *
 * Handles registration and bootstrapping of the Cortex Foundation package.
 * Compatible with Laravel 12.
 */
class FoundationServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/cortex.php',
            'cortex'
        );

        // Register singleton instances
        $this->app->singleton('cortex', function ($app) {
            return new \Cortex\Foundation\Cortex($app);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Publish configuration
        $this->publishes([
            __DIR__ . '/../../config/cortex.php' => config_path('cortex.php'),
        ], 'cortex-config');

        // Publish views if they exist
        if (is_dir(__DIR__ . '/../../resources/views')) {
            $this->loadViewsFrom(
                __DIR__ . '/../../resources/views',
                'cortex'
            );

            $this->publishes([
                __DIR__ . '/../../resources/views' => resource_path('views/vendor/cortex'),
            ], 'cortex-views');
        }
    }
}
