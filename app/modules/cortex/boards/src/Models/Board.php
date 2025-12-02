<?php

declare(strict_types=1);

namespace Cortex\Boards\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Cortex\Foundation\Traits\Auditable;
use Cortex\Boards\Models\Post;
use Rinvex\Support\Traits\HashidsTrait;
use Rinvex\Support\Traits\HasTimezones;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Cortex\Boards\Models\Board
 */
class Board extends Model
{
    use Auditable;
    use HashidsTrait;
    use HasTimezones;
    use LogsActivity;

    protected $table = 'cortex_boards';

    protected $fillable = ['name', 'slug', 'description', 'owner_id', 'owner_type'];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function owner()
    {
        return $this->morphTo('owner', 'owner_type', 'owner_id', 'id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }
}
