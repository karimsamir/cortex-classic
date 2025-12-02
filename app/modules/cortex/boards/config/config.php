<?php

declare(strict_types=1);

return [
    // Module models mapping used by registerModels() helper
    'models' => [
        'board' => \Cortex\Boards\Models\Board::class,
        'post' => \Cortex\Boards\Models\Post::class,
        'comment' => \Cortex\Boards\Models\Comment::class,
    ],
];
