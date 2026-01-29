<?php

namespace Rinvex\Laravel{PascalCase}\Providers;

use Illuminate\Support\ServiceProvider;

class {PascalCase}ServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Remove deprecated publishAssets() calls
        // Use publishesToGroups() or direct publish() instead
        $this->publishes([
            __DIR__.'/../../config' => config_path(),
            __DIR__.'/../../database/migrations' => database_path('migrations'),
        ], '{package-name}');
    }

    public function register(): void
    {
        // Register bindings
    }
}
