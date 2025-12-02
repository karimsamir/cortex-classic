<?php

declare(strict_types=1);

use Cortex\Boards\Http\Controllers\Frontarea\PostsController;
use Cortex\Boards\Http\Controllers\Frontarea\PagesMediaController;

// dd("Karim" . __FILE__);
Route::domain('{frontarea}')->group(function () {
    Route::name('frontarea.')
         ->middleware(['web', 'nohttpcache'])
         ->prefix(route_prefix('frontarea'))->group(function () {
             // Boards Routes
             Route::name('cortex.boards.posts.')->prefix('posts')->group(function () {
                 Route::get('/')->name('index')->uses([PostsController::class, 'index']);
                 Route::get('create')->name('create')->middleware('auth')->uses([PostsController::class, 'create']);
                 Route::post('create')->name('store')->middleware('auth')->uses([PostsController::class, 'store']);
                 Route::get('{post}')->name('show')->uses([PostsController::class, 'show']);
                 Route::get('{post}/edit')->name('edit')->uses([PostsController::class, 'edit']);
                 Route::put('{post}/edit')->name('update')->uses([PostsController::class, 'update']);
                 Route::delete('{post}')->name('destroy')->uses([PostsController::class, 'destroy']);

                 // Comments
                 Route::post('{post}/comments')->name('comments.store')->middleware('auth')->uses([\Cortex\Boards\Http\Controllers\Frontarea\CommentsController::class, 'store']);
                 Route::delete('{post}/comments/{comment}')->name('comments.destroy')->middleware('auth')->uses([\Cortex\Boards\Http\Controllers\Frontarea\CommentsController::class, 'destroy']);

             });
         });
});
