<?php

declare(strict_types=1);

use Cortex\Auth\Http\Middleware\ScopeBouncer;
use Illuminate\Database\Eloquent\Relations\Relation;

return function () {
    // Bind route models and constrains
    Route::pattern('manager', '[a-zA-Z0-9-_]+');
    Route::model('manager', config('cortex.auth.models.manager'));

    // Map relations
    Relation::morphMap([
        'manager' => config('cortex.auth.models.manager'),
    ]);

    if (! $this->app->runningInConsole()) {
        // Append middleware to the 'web' middleware group
        Route::pushMiddlewareToGroup('web', ScopeBouncer::class);
    }
};
