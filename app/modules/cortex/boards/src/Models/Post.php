<?php

declare(strict_types=1);

namespace Cortex\Boards\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Cortex\Foundation\Traits\Auditable;
use Cortex\Boards\Models\Comment;
use Rinvex\Support\Traits\HashidsTrait;
use Rinvex\Support\Traits\HasTimezones;
use Spatie\Activitylog\Traits\LogsActivity;

class Post extends Model
{
    use Auditable;
    use HashidsTrait;
    use HasTimezones;
    use LogsActivity;

    protected $table = 'cortex_boards_posts';

    protected $fillable = ['title', 'slug', 'content', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Boot model events to set post owner from current authenticated user.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $user = request()->user();

            if ($user && (empty($model->created_by_id) || empty($model->created_by_type))) {
                $model->created_by_id = $user->getKey();
                $model->created_by_type = $user->getMorphClass();
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    /**
     * Return the morph class used for polymorphic relations.
     *
     * Overriding this prevents Eloquent from requiring a morph map entry
     * for this model when storing polymorphic relations.
     */
    public function getMorphClass(): string
    {
        return static::class;
    }
}
