<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Course extends Model implements HasMedia
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
        return ($this->getMedia('course-image')->first())
            ? $this->getMedia('course-image')->first()->getUrl($size)
            : '';
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('course-image')
            ->useDisk('public')
            ->singleFile();
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }
}
