<?php

declare(strict_types=1);

namespace Cortex\Foundation;

use Illuminate\Container\Container;

/**
 * Cortex Main Class
 *
 * Central point for Cortex functionality
 */
class Cortex
{
    protected Container $app;

    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * Get the Cortex version
     */
    public function version(): string
    {
        return '12.0.0-custom';
    }

    /**
     * Get a configuration value
     */
    public function config(string $key, mixed $default = null): mixed
    {
        return config('cortex.' . $key, $default);
    }

    /**
     * Check if a feature is enabled
     */
    public function isFeatureEnabled(string $feature): bool
    {
        return (bool) $this->config('features.' . $feature, false);
    }

    /**
     * Get the modules path
     */
    public function modulesPath(): string
    {
        return $this->config('modules.path', app_path('modules'));
    }
}
