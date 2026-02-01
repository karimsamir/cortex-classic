<?php

declare(strict_types=1);

use Cortex\Auth\Models\Manager;
use Diglactic\Breadcrumbs\Generator;
use Diglactic\Breadcrumbs\Breadcrumbs;

Breadcrumbs::for('adminarea.cortex.auth.managers.index', function (Generator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.home');
    $breadcrumbs->push(trans('cortex/auth::tenantable.managers'), route('adminarea.cortex.auth.managers.index'));
});

Breadcrumbs::for('adminarea.cortex.auth.managers.import', function (Generator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.cortex.auth.managers.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('adminarea.cortex.auth.managers.import'));
});

Breadcrumbs::for('adminarea.cortex.auth.managers.import.logs', function (Generator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.cortex.auth.managers.import');
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('adminarea.cortex.auth.managers.import.logs'));
});

Breadcrumbs::for('adminarea.cortex.auth.managers.create', function (Generator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.cortex.auth.managers.index');
    $breadcrumbs->push(trans('cortex/auth::tenantable.create_manager'), route('adminarea.cortex.auth.managers.create'));
});

Breadcrumbs::for('adminarea.cortex.auth.managers.edit', function (Generator $breadcrumbs, Manager $manager) {
    $breadcrumbs->parent('adminarea.cortex.auth.managers.index');
    $breadcrumbs->push(strip_tags($manager->username), route('adminarea.cortex.auth.managers.edit', ['manager' => $manager]));
});

Breadcrumbs::for('adminarea.cortex.auth.managers.logs', function (Generator $breadcrumbs, Manager $manager) {
    $breadcrumbs->parent('adminarea.cortex.auth.managers.edit', $manager);
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('adminarea.cortex.auth.managers.logs', ['manager' => $manager]));
});

Breadcrumbs::for('adminarea.cortex.auth.managers.activities', function (Generator $breadcrumbs, Manager $manager) {
    $breadcrumbs->parent('adminarea.cortex.auth.managers.edit', $manager);
    $breadcrumbs->push(trans('cortex/auth::common.activities'), route('adminarea.cortex.auth.managers.activities', ['manager' => $manager]));
});
