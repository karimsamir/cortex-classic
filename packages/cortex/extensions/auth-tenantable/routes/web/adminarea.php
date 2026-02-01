<?php

declare(strict_types=1);

use Cortex\Auth\Http\Controllers\Adminarea\ManagersController;
use Cortex\Auth\Http\Controllers\Adminarea\ManagersMediaController;

Route::domain('{adminarea}')->group(function () {
    Route::name('adminarea.')
         ->middleware(['web', 'nohttpcache'])
         ->prefix(route_prefix('adminarea'))->group(function () {
             Route::middleware(['can:access-adminarea'])->group(function () {
                 // Managers Routes
                 Route::name('cortex.auth.managers.')->prefix('managers')->group(function () {
                     Route::match(['get', 'post'], '/')->name('index')->uses([ManagersController::class, 'index']);
                     Route::post('import')->name('import')->uses([ManagersController::class, 'import']);
                     Route::get('create')->name('create')->uses([ManagersController::class, 'create']);
                     Route::post('create')->name('store')->uses([ManagersController::class, 'store']);
                     Route::get('{manager}')->name('show')->uses([ManagersController::class, 'show']);
                     Route::get('{manager}/edit')->name('edit')->uses([ManagersController::class, 'edit']);
                     Route::put('{manager}/edit')->name('update')->uses([ManagersController::class, 'update']);
                     Route::match(['get', 'post'], '{manager}/logs')->name('logs')->uses([ManagersController::class, 'logs']);
                     Route::match(['get', 'post'], '{manager}/activities')->name('activities')->uses([ManagersController::class, 'activities']);
                     Route::delete('{manager}')->name('destroy')->uses([ManagersController::class, 'destroy']);
                     Route::delete('{manager}/media/{media}')->name('media.destroy')->uses([ManagersMediaController::class, 'destroy']);
                 });
             });
         });
});
