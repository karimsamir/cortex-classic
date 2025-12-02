<?php

declare(strict_types=1);

use Cortex\Boards\Http\Controllers\Adminarea\PostsController;

Route::domain('{adminarea}')->group(function () {
    Route::name('adminarea.')
         ->middleware(['web', 'nohttpcache', 'can:access-adminarea'])
         ->prefix(route_prefix('adminarea'))->group(function () {
             // Boards Posts Routes
             Route::name('cortex.boards.posts.')->prefix('posts')->group(function () {
                 Route::match(['get', 'post'], '/')->name('index')->uses([PostsController::class, 'index']);
                 Route::get('create')->name('create')->uses([PostsController::class, 'create']);
                 Route::post('create')->name('store')->uses([PostsController::class, 'store']);

                 Route::get('{post}/edit')->name('edit')->uses([PostsController::class, 'edit']);
                 Route::put('{post}/edit')->name('update')->uses([PostsController::class, 'update']);
                 Route::delete('{post}')->name('destroy')->uses([PostsController::class, 'destroy']);
             });
         });
});
