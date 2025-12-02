<?php

declare(strict_types=1);

namespace Cortex\Boards\Http\Controllers\Frontarea;

use Cortex\Boards\Models\Comment;
use Cortex\Boards\Models\Post;
use Cortex\Foundation\Http\Controllers\AbstractController;
use Cortex\Boards\Http\Requests\Frontarea\CommentFormRequest;

class CommentsController extends AbstractController
{
    public function store(CommentFormRequest $request, Post $post)
    {
        $data = $request->validated();
        $comment = app('cortex.boards.comment')->fill(array_merge($data, ['post_id' => $post->getKey()]));

        // Ensure audit creator/updater fields are set (some environments may not
        // reliably populate these via the Auditable trait), set them explicitly
        // from the authenticated user if present.
        $user = $request->user();
        if ($user) {
            $comment->created_by_id = $user->getKey();
            $comment->created_by_type = $user->getMorphClass();

            $comment->updated_by_id = $user->getKey();
            $comment->updated_by_type = $user->getMorphClass();
        }

        $comment->save();

        return intend([ 'back' => true, 'with' => ['success' => trans('cortex/foundation::messages.resource_saved')] ]);
    }

    public function destroy(Post $post, Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return intend([ 'back' => true, 'with' => ['warning' => trans('cortex/foundation::messages.resource_deleted')] ]);
    }
}
