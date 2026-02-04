<?php

declare(strict_types=1);

use Cortex\Auth\Http\Controllers\Frontarea\TenantRegistrationController;

Route::domain('{frontarea}')->group(function () {
    Route::name('frontarea.')
        ->middleware(['web', 'nohttpcache'])
        ->prefix(route_prefix('frontarea'))->group(function () {
            Route::name('cortex.auth.account.')->group(function () {
                // We can't register these two routes inside the managerarea, since the managerarea
                // is accessible only through the tenant domain/subdomain, and we did not create the tenant yet!
                Route::get('register/tenant')->name('register.tenant')->uses([TenantRegistrationController::class, 'form']);
                Route::post('register/tenant')->name('register.tenant.process')->uses([TenantRegistrationController::class, 'register']);
            });
        });
});
