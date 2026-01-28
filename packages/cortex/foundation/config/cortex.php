<?php

declare(strict_types=1);

return [
    /**
     * Cortex Package Configuration
     *
     * This is a custom build for Laravel 12 compatibility
     */

    // Package name and version
    'name' => 'Cortex',
    'version' => '12.0.0-custom',
    'description' => 'Cortex Foundation for Laravel 12',

    /**
     * Module Configuration
     */
    'modules' => [
        'enabled' => true,
        'path' => app_path('modules'),
        'namespace' => 'Modules',
    ],

    /**
     * Feature Flags
     */
    'features' => [
        'multi_language' => true,
        'multi_tenant' => false,
        'audit_logging' => true,
        'soft_deletes' => true,
    ],

    /**
     * Database Configuration
     */
    'database' => [
        'prefix' => 'cortex_',
    ],

    /**
     * Cache Configuration
     */
    'cache' => [
        'enabled' => true,
        'ttl' => 3600,
    ],
];
