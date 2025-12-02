<?php

namespace Cortex\Boards\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Rinvex\Support\Traits\ConsoleTools;
use Cortex\Boards\Models\Board;
use Cortex\Boards\Models\Post;
use Cortex\Boards\Models\Comment;
use Cortex\Boards\Policies\PostPolicy;
use Cortex\Boards\Policies\CommentPolicy;

class BoardsServiceProvider extends ServiceProvider
{
    use ConsoleTools;

    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind eloquent models into the container so other parts of the app can resolve them
        $this->registerModels([
            'cortex.boards.board' => Board::class,
            'cortex.boards.post' => Post::class,
            'cortex.boards.comment' => Comment::class,
        ]);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register policies
        Gate::policy(Post::class, PostPolicy::class);
        Gate::policy(Comment::class, CommentPolicy::class);
    }
}
