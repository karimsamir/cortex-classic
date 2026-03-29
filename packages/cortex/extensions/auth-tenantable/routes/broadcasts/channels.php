<?php

declare(strict_types=1);

use Illuminate\Contracts\Auth\Access\Authorizable;

Broadcast::channel('cortex.auth.managers.index', function (Authorizable $user) {
    return $user->can('list', app('cortex.auth.manager'));
});
