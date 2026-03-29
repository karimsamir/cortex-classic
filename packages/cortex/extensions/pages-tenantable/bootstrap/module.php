<?php

declare(strict_types=1);

return function () {
    // Share tenants with page view
    $this->app['view']->composer('cortex/pages::adminarea.pages.page', function ($view) {
        $view->with('tenants', app('rinvex.tenants.tenant')->all()->pluck('name', 'id'));
    });
};
