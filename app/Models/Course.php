<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Image\Enums\Fit;
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
            ->fit(Fit::Max, 180, 180)
            ->quality(80)
            ->optimize();

        $this->addMediaConversion('medium')
            ->fit(Fit::Max, 300)
            ->quality(80)
            ->optimize();

        $this->addMediaConversion('large')
            ->fit(Fit::Max, 600)
            ->quality(80)
            ->optimize();
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

    public function accesses(): HasMany
    {
        return $this->hasMany(CourseAccess::class);
    }

    public function isFree(): bool
    {
        return data_get($this->extra, 'access') !== 'paid';
    }

    public function requiresAccess(): bool
    {
        return ! $this->isFree();
    }

    public function price(): ?float
    {
        $price = data_get($this->extra, 'price');

        return is_numeric($price) ? (float) $price : null;
    }

    public function hasPrice(): bool
    {
        return $this->price() !== null && $this->price() > 0;
    }

    public function formattedPrice(): ?string
    {
        return $this->hasPrice()
            ? 'R$ '.number_format($this->price(), 2, ',', '.')
            : null;
    }
}
