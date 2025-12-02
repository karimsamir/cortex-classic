<article>
    <h1>Post policy</h1>
    <h1>{{ $post->title }}</h1>
    <div>{!! $post->content !!}</div>

    <section id="comments">
        <h3>Comments</h3>
        @foreach($post->comments as $comment)
            <div class="comment">{{ $comment->body }} —
                <small>{{ $comment->creator?->username ?? 'Guest' }}</small>
                @can('delete', $comment)
                    <form action="{{ route('frontarea.cortex.boards.posts.comments.destroy', ['post' => $post->getRouteKey(), 'comment' => $comment->getRouteKey()]) }}" method="POST" style="display:inline;" onsubmit="return confirm('{{ trans('cortex/foundation::messages.delete_confirmation') }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-xs">{{ trans('cortex/foundation::common.delete') }}</button>
                    </form>
                @endcan
            </div>
            {{-- <h1>@dump($comment->creator->username)</h1> --}}
        @endforeach
    </section>

    <section id="add-comment">
        <form method="POST" action="{{ route('frontarea.cortex.boards.posts.comments.store', $post->getRouteKey()) }}">
            @csrf
            <textarea name="body" required></textarea>
            <button type="submit">Add comment</button>
        </form>
    </section>

    @can('delete', $post)
    <section id="delete-post">
        <form method="POST" action="{{ route('frontarea.cortex.boards.posts.destroy', $post->getRouteKey()) }}" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('Are you sure you want to delete this post?');" style="background-color: #dc3545; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer;">Delete Post</button>
        </form>
    </section>
    @endcan
</article>
