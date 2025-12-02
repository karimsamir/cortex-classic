<h1>Boards — Posts</h1>
@foreach($posts as $post)
    <article>
        <h2><a href="{{ route('frontarea.cortex.boards.posts.show', [$post->getRouteKey()]) }}">{{ $post->title }}</a></h2>
        <div>{{ Str::limit(strip_tags($post->content), 200) }}</div>

        @can('delete', $post)
            <form action="{{ route('frontarea.cortex.boards.posts.destroy', ['post' => $post->getRouteKey()]) }}" method="POST" style="display:inline;" onsubmit="return confirm('{{ trans('cortex/foundation::messages.delete_confirmation') }}')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-xs">{{ trans('cortex/foundation::common.delete') }}</button>
            </form>
        @endcan
    </article>
@endforeach

{{ $posts->links() }}
