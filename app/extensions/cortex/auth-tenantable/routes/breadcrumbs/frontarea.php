<?php

declare(strict_types=1);

use Diglactic\Breadcrumbs\Generator;
use Diglactic\Breadcrumbs\Breadcrumbs;

Breadcrumbs::for('frontarea.cortex.auth.account.register.tenant', function (Generator $breadcrumbs) {
    $breadcrumbs->parent('frontarea.home');
    $breadcrumbs->push(trans('cortex/auth::common.register'), route('frontarea.cortex.auth.account.register.tenant'));
});
