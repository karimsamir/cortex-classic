<form method="POST" action="{{ $post->exists ? route('frontarea.cortex.boards.posts.update', $post->getRouteKey()) : route('frontarea.cortex.boards.posts.store') }}">
    @csrf
    @if($post->exists)
        @method('PUT')
    @endif

    <label>Title</label>
    <input name="title" value="{{ old('title', $post->title) }}" required />

    <label>Content</label>
    <textarea name="content">{{ old('content', $post->content) }}</textarea>

    <button type="submit">Save</button>
</form>
