<?php

declare(strict_types=1);

if (!function_exists('cortex')) {
    /**
     * Get Cortex instance or a configuration value
     */
    function cortex(?string $key = null): mixed
    {
        $cortex = app('cortex');

        if ($key === null) {
            return $cortex;
        }

        return $cortex->config($key);
    }
}
