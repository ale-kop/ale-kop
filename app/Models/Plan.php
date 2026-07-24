<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('price');
    }

    public function formattedPrice(): string
    {
        return 'R$ '.number_format((float) $this->price, 2, ',', '.');
    }

    public function intervalLabel(): ?string
    {
        return match ($this->interval) {
            'month' => '/mês',
            'year' => '/ano',
            default => null,
        };
    }
}
