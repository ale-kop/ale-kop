<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ContentImage extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function url(string $size = 'large'): string
    {
        return ($this->getMedia('content-image')->first())
            ? $this->getMedia('content-image')->first()->getUrl($size)
            : '';
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('medium')
            ->fit(Fit::Max, 700, 500)
            ->quality(80)
            ->optimize();

        $this->addMediaConversion('large')
            ->fit(Fit::Max, 1100, 1100)
            ->quality(80)
            ->optimize();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('content-image')
            ->singleFile();
    }
}
