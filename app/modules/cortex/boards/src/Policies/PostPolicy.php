<?php

declare(strict_types=1);

namespace Cortex\Boards\Policies;

use Cortex\Boards\Models\Post;

class PostPolicy
{
    public function view($user, Post $post): bool
    {
        // public area posts may be visible to anyone, fallback to true
        return (bool) $post->is_active;
    }

    public function create($user): bool
    {
        // allow authenticated users to create posts
        return (bool) $user;
    }

    public function update($user, Post $post): bool
    {
        // authors can update their posts
        if ($user?->getKey() && $post->created_by_id && $post->created_by_type) {

            if ($user->getKey() === $post->created_by_id && $user->getMorphClass() === $post->created_by_type) {
                return true;
            }
        }

        // post owner can update posts belonging to their post
        if ($post->created_by_id && $post->created_by_type) {
            if ($user?->getKey() === $post->created_by_id && $user->getMorphClass() === $post->created_by_type) {
                return true;
            }
        }

        return false;
    }

    public function delete($user, Post $post): bool
    {
        // post owner can delete any post on their post
        if ($post->created_by_id && $post->created_by_type) {

            if ($user?->getKey() === $post->created_by_id && $user->getMorphClass() === $post->created_by_type) {
                return true;
            }
        }

        // author can delete own post
        if ($user?->getKey() && $post->created_by_id && $post->created_by_type) {
            if ($user->getKey() === $post->created_by_id && $user->getMorphClass() === $post->created_by_type) {
                return true;
            }
        }

        return false;
    }
}
