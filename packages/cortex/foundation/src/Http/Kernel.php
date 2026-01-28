<?php

namespace Cortex\Foundation\Http;

use Illuminate\Foundation\Http\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    protected $middleware = [];

    protected $middlewareGroups = [
        'web' => [],
        'api' => [],
    ];

    protected $routeMiddleware = [];

    protected $middlewarePriority = [];
}
