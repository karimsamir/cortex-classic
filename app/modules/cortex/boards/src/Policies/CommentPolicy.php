<?php

declare(strict_types=1);

namespace Cortex\Boards\Policies;

use Cortex\Boards\Models\Comment;

class CommentPolicy
{
    public function create($user): bool
    {
        return (bool) $user;
    }

    public function delete($user, Comment $comment): bool
    {
        // Board owner can delete any comment on their posts
        if ($comment->post && $comment->post->board && $comment->post->board->owner_id && $comment->post->board->owner_type) {
            if ($user?->getKey() === $comment->post->board->owner_id && $user->getMorphClass() === $comment->post->board->owner_type) {
                return true;
            }
        }

        // Comment author can delete their own comment
        if ($user?->getKey() && $comment->created_by_id && $comment->created_by_type) {
            if ($user->getKey() === $comment->created_by_id && $user->getMorphClass() === $comment->created_by_type) {
                return true;
            }
        }

        return false;
    }
}
