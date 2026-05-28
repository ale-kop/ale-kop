<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NewsletterCampaign extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'sent_at' => 'datetime',
        ];
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function lists(): BelongsToMany
    {
        return $this->belongsToMany(NewsletterList::class, 'campaign_list', 'campaign_id', 'list_id');
    }

    public function sendLogs(): HasMany
    {
        return $this->hasMany(NewsletterSendLog::class, 'campaign_id');
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isScheduled(): bool
    {
        return $this->status === 'scheduled';
    }

    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    public function completionPercentage(): int
    {
        if ($this->total_recipients === 0) {
            return 0;
        }

        return (int) round((($this->sent_count + $this->failed_count) / $this->total_recipients) * 100);
    }

    public function pendingCount(): int
    {
        return max(0, $this->total_recipients - $this->sent_count - $this->failed_count);
    }
}
