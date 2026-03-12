<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Spatie\Image\Enums\Fit;
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
            'is_read' => 'boolean',
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
            ->fit(Fit::Max, 180, 180)
            ->quality(80)
            ->optimize();

        $this->addMediaConversion('medium')
            ->fit(Fit::Max, 497, 290)
            ->quality(80)
            ->optimize();

        $this->addMediaConversion('large')
            ->fit(Fit::Max, 1100, 500)
            ->quality(80)
            ->optimize();
    }

    public function getCacheKeyForPost(): string
    {
        return 'posts.' . $this->id;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('post-image')
            ->useDisk('public')
            ->singleFile();
    }

    public function readers()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function isReadBy(?int $userId): bool
    {
        if (! $userId) {
            return false;
        }

        return $this->readers()->where('user_id', $userId)->exists();
    }

    /**
     * Attach an efficient boolean "is_read" flag using withExists.
     * Does not override the caller's select.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|QueryBuilder  $query
     * @return mixed
     */
    public function scopeWithReadFlag($query, ?int $userId)
    {
        if ($userId) {
            return $query->withExists([
                'readers as is_read' => fn($q) => $q->whereKey($userId),
            ]);
        }

        return $query;
    }
}
