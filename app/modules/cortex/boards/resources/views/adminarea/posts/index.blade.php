<h1>Admin — Boards Posts</h1>
@foreach($posts as $post)
    <div>
        <strong>{{ $post->title }}</strong>
        <a href="{{ route('adminarea.cortex.boards.posts.edit', $post->getRouteKey()) }}">Edit</a>
        <form action="{{ route('adminarea.cortex.boards.posts.destroy', $post->getRouteKey()) }}" method="POST" style="display:inline">
            @csrf
            @method('DELETE')
            <button type="submit">Delete</button>
        </form>
    </div>
@endforeach

{{ $posts->links() }}
