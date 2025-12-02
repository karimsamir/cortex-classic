<?php

declare(strict_types=1);

namespace Cortex\Boards\Http\Controllers\Adminarea;

use Illuminate\Http\Request;
use Cortex\Boards\Models\Post;
use Cortex\Foundation\Http\Controllers\AuthorizedController;
use Cortex\Boards\Http\Requests\Adminarea\PostFormRequest;

class PostsController extends AuthorizedController
{
    protected $resource = 'cortex.boards.models.post';

    public function index()
    {
        // Minimal listing for now
        $posts = Post::latest('created_at')->paginate(25);

        return view('cortex/boards::adminarea.posts.index', compact('posts'));
    }

    public function create(Request $request, Post $post)
    {
        return view('cortex/boards::adminarea.posts.form', compact('post'));
    }

    public function store(PostFormRequest $request, Post $post)
    {
        $post->fill($request->validated());
        $post->save();

        return intend([ 'url' => route('adminarea.cortex.boards.posts.index'), 'with' => ['success' => trans('cortex/foundation::messages.resource_saved')] ]);
    }

    public function edit(Request $request, Post $post)
    {
        return view('cortex/boards::adminarea.posts.form', compact('post'));
    }

    public function update(PostFormRequest $request, Post $post)
    {
        $post->fill($request->validated())->save();

        return intend([ 'back' => true, 'with' => ['success' => trans('cortex/foundation::messages.resource_saved')] ]);
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return intend([ 'url' => route('adminarea.cortex.boards.posts.index'), 'with' => ['warning' => trans('cortex/foundation::messages.resource_deleted')] ]);
    }
}
