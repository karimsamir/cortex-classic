<?php

declare(strict_types=1);

use Cortex\Auth\Models\Manager;
use Rinvex\Menus\Models\MenuItem;
use Rinvex\Menus\Models\MenuGenerator;

Menu::register('adminarea.sidebar', function (MenuGenerator $menu) {
    $menu->findByTitleOrAdd(trans('cortex/foundation::common.user'), 20, 'fa fa-users', 'header', [], [], function (MenuItem $dropdown) {
        $dropdown->route(['adminarea.cortex.auth.managers.index'], trans('cortex/auth::tenantable.managers'), 15, 'fa fa-user')->ifCan('list', app('cortex.auth.manager'))->activateOnRoute('adminarea.cortex.auth.managers');
    });
});

Menu::register('adminarea.cortex.auth.managers.tabs', function (MenuGenerator $menu, Manager $manager) {
    $menu->route(['adminarea.cortex.auth.managers.import'], trans('cortex/auth::common.records'))->ifCan('import', $manager)->if(Route::is('adminarea.cortex.auth.managers.import*'));
    $menu->route(['adminarea.cortex.auth.managers.import.logs'], trans('cortex/auth::common.logs'))->ifCan('audit', $manager)->if(Route::is('adminarea.cortex.auth.managers.import*'));
    $menu->route(['adminarea.cortex.auth.managers.create'], trans('cortex/auth::common.details'))->ifCan('create', $manager)->if(Route::is('adminarea.cortex.auth.managers.create'));
    $menu->route(['adminarea.cortex.auth.managers.edit', ['manager' => $manager]], trans('cortex/auth::common.details'))->ifCan('update', $manager)->if($manager->exists);
    $menu->route(['adminarea.cortex.auth.managers.logs', ['manager' => $manager]], trans('cortex/auth::common.logs'))->ifCan('audit', $manager)->if($manager->exists);
    $menu->route(['adminarea.cortex.auth.managers.activities', ['manager' => $manager]], trans('cortex/auth::common.activities'))->ifCan('audit', $manager)->if($manager->exists);
});
