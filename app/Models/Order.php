<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Order extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_REJECTED = 'rejected';

    public const STATUS_CANCELLED = 'cancelled';

    public const STATUS_REFUNDED = 'refunded';

    protected $guarded = [];

    protected $hidden = ['pix_qr_code_base64'];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'installments' => 'integer',
            'pix_expires_at' => 'datetime',
            'paid_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            $order->uuid ??= (string) Str::uuid();
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isPix(): bool
    {
        return $this->payment_method === 'pix';
    }

    public function firstName(): string
    {
        return Str::of($this->name)->trim()->before(' ')->toString();
    }

    public function formattedAmount(): string
    {
        return 'R$ '.number_format((float) $this->amount, 2, ',', '.');
    }

    /**
     * Status do Mercado Pago → status do pedido.
     * authorized (pré-autorização) e in_process continuam pendentes.
     */
    public static function statusFromPayment(string $mpStatus): string
    {
        return match ($mpStatus) {
            'approved' => self::STATUS_APPROVED,
            'rejected' => self::STATUS_REJECTED,
            'cancelled' => self::STATUS_CANCELLED,
            'refunded', 'charged_back' => self::STATUS_REFUNDED,
            default => self::STATUS_PENDING,
        };
    }
}
