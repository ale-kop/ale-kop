<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Spatie\Image\Enums\CropPosition;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Post extends Model implements HasMedia
{
    use InteractsWithMedia, SoftDeletes;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'meta' => 'array',
            'extra' => 'array',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function image(string $size = 'thumb'): string
    {
        return ($this->getMedia('post-image')->first())
            ? $this->getMedia('post-image')->first()->getUrl($size)
            : '';
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->crop(180, 180, CropPosition::Center);

        $this->addMediaConversion('medium')
            ->crop(497, 290, CropPosition::Center);

        $this->addMediaConversion('large')
            ->crop(1100, 500, CropPosition::Center);
    }

    public function getCacheKeyForPost(): string
    {
        return 'posts.'.$this->id;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('post-image')
            ->useDisk('public')
            ->singleFile();
    }

    public function readers()
    {
        return $this->belongsToMany(User::class, 'post_user_reads')->withTimestamps();
    }

    public function isReadBy(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        return $this->readers()->where('user_id', $user->id)->exists();
    }

    /**
     * Attach an efficient boolean "is_read" flag using withExists and select only required columns.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|QueryBuilder  $query
     * @param  int|null  $userId
     * @param  array|null  $columns  Minimal columns to select (must include keys like id, course_id, tag_id when needed)
     * @return mixed
     */
    public function scopeWithReadFlag($query, ?int $userId, ?array $columns = null)
    {
        $table = $this->getTable();

        // Default minimal set useful for most listings (id/slug/name + FKs used by relations and meta for description)
        $default = [
            "$table.id",
            "$table.slug",
            "$table.name",
            "$table.course_id",
            "$table.tag_id",
            "$table.section_id",
            "$table.meta",
        ];

        $query->select($columns ?: $default);

        if (! $userId) {
            return $query->addSelect(['is_read' => \DB::raw('false')]);
        }

        return $query->withExists([
            'readers as is_read' => fn ($q) => $q->where('users.id', $userId),
        ]);
    }
}
