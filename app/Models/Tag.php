<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Enums\Fit;

class Tag extends Model implements HasMedia
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

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
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

    public function image(string $size = 'thumb'): string
    {
        return ($this->getMedia('tag-image')->first())
            ? $this->getMedia('tag-image')->first()->getUrl($size)
            : '';
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('tag-image')
            ->useDisk('public')
            ->singleFile();
    }
}
