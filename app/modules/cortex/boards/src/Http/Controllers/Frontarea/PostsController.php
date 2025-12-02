<?php

namespace Cortex\Boards\Http\Controllers\Frontarea;

use Illuminate\Http\Request;
use Cortex\Boards\Models\Post;
use Cortex\Foundation\Http\Controllers\AbstractController;
use Cortex\Boards\Http\Requests\Frontarea\PostFormRequest;

class PostsController extends AbstractController
{
    public function index(Request $request)
    {
        $posts = Post::query()->where('is_active', true)->latest('created_at')->paginate(15);

        return view('cortex/boards::frontarea.posts.index', compact('posts'));
    }

    public function create(Request $request)
    {
        $post = app('cortex.boards.post');

        return view('cortex/boards::frontarea.posts.form', compact('post'));
    }

    public function store(PostFormRequest $request, Post $post)
    {
        $data = $request->validated();

        $post->fill($data);
        $post->save();

        // Pass route parameters as an array to avoid UrlGenerator receiving a string
        return intend([ 'url' => route('frontarea.cortex.boards.posts.show', [$post->getRouteKey()]), 'with' => ['success' => trans('cortex/foundation::messages.resource_saved')] ]);
    }

    public function show(Post $post)
    {
        // Eager-load comments and their creators to prevent N+1 queries
        $post->load(['comments.createdBy', 'comments.post']);

        return view('cortex/boards::frontarea.posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);

        return view('cortex/boards::frontarea.posts.form', compact('post'));
    }

    public function update(PostFormRequest $request, Post $post)
    {
        $this->authorize('update', $post);

        $post->fill($request->validated())->save();

        return intend([ 'back' => true, 'with' => ['success' => trans('cortex/foundation::messages.resource_saved')] ]);
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        $post->delete();

        return intend([ 'url' => route('frontarea.cortex.boards.posts.index'), 'with' => ['warning' => trans('cortex/foundation::messages.resource_deleted')] ]);
    }
}
