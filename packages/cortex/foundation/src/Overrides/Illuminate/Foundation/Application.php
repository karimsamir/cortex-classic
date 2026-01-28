<?php

namespace Cortex\Foundation\Overrides\Illuminate\Foundation;

use Illuminate\Foundation\Application as BaseApplication;

/**
 * Cortex Application Override
 *
 * Extends Laravel's Application class with Cortex-specific functionality
 */
class Application extends BaseApplication
{
    /**
     * Constructor
     */
    public function __construct($basePath = null)
    {
        parent::__construct($basePath);
    }
}
