<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

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
            ->width(250)
            ->sharpen(10);

        $this->addMediaConversion('large')
            ->width(700)
            ->sharpen(10);
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
