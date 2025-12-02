<form method="POST" action="{{ $post->exists ? route('adminarea.cortex.boards.posts.update', $post->getRouteKey()) : route('adminarea.cortex.boards.posts.store') }}">
    @csrf
    @if($post->exists)
        @method('PUT')
    @endif

    <label>Title</label>
    <input name="title" value="{{ old('title', $post->title) }}" required />

    <label>Content</label>
    <textarea name="content">{{ old('content', $post->content) }}</textarea>

    <label>Board</label>
    <input type="number" name="board_id" value="{{ old('board_id', $post->board_id) }}" />

    <button type="submit">Save</button>
</form>
