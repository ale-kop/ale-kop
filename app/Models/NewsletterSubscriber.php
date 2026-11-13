<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class NewsletterSubscriber extends Model
{
    protected $guarded = [];

    protected $hidden = ['verification_code'];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'subscribed_at' => 'datetime',
            'email_verified_at' => 'datetime',
            'verification_expires_at' => 'datetime',
            'verification_sent_at' => 'datetime',
            'unsubscribed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $subscriber) {
            if (empty($subscriber->unsubscribe_token)) {
                $subscriber->unsubscribe_token = Str::random(64);
            }
            if (empty($subscriber->subscribed_at)) {
                $subscriber->subscribed_at = now();
            }
        });
    }

    public function lists(): BelongsToMany
    {
        return $this->belongsToMany(NewsletterList::class, 'list_subscriber', 'subscriber_id', 'list_id');
    }

    public function sendLogs(): HasMany
    {
        return $this->hasMany(NewsletterSendLog::class, 'subscriber_id');
    }

    /** Post (ou aula) em cuja página a pessoa se cadastrou, quando a origem é um post. */
    public function sourcePost(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'source_post_id');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
