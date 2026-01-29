# Board Module: Posts & Comments Feature

Implementation guide for the posts and comments feature in `app/modules/cortex/boards/`.

## Models with Relationships

```php
// app/modules/cortex/boards/src/Models/Post.php
namespace Cortex\Boards\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Post extends Model {
    use HasTranslations;

    public array $translatable = ['title', 'content'];

    protected $fillable = ['board_id', 'author_id', 'title', 'content'];

    public function board() {
        return $this->belongsTo(Board::class);
    }

    public function author() {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function comments(): HasMany {
        return $this->hasMany(Comment::class);
    }
}
```

## Authorization with Bouncer

Use Bouncer in controllers to check ownership:

```php
// In PostController
public function destroy(Post $post) {
    // Board owner or admin can delete any post
    if (! (auth()->user()->can('delete-post') ||
           auth()->user()->id === $post->board->owner_id)) {
        abort(403);
    }

    $post->delete();
    return redirect()->back();
}
```

Define abilities in module's service provider:

```php
// In service provider boot()
Bouncer::define('delete-post', function ($user, $post) {
    return $user->id === $post->board->owner_id || $user->admin;
});
```

## Broadcasting Post Creation

```php
// app/modules/cortex/boards/src/Events/PostCreated.php
namespace Cortex\Boards\Events;

use Cortex\Boards\Models\Post;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithBroadcasting;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PostCreated implements ShouldBroadcast {
    use InteractsWithBroadcasting;

    public function __construct(public Post $post) {}

    public function broadcastOn(): Channel {
        return new Channel('board.' . $this->post->board_id);
    }
}
```

Broadcast from controller:

```php
public function store(StorePostRequest $request, Board $board) {
    $post = $board->posts()->create($request->validated());

    PostCreated::dispatch($post);

    return redirect()->back();
}
```

## Frontend Event Listening

In module's `resources/js/module.js`:

```javascript
export default async function() {
    // Listen for new posts on current board
    const boardId = document.querySelector('[data-board-id]')?.dataset.boardId;

    if (boardId) {
        window.Echo.channel(`board.${boardId}`)
            .listen('PostCreated', (event) => {
                // Append new post to DOM or reload list
                location.reload();
            });
    }
}
```
