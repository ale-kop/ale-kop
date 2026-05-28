<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class NewsletterList extends Model
{
    protected $guarded = [];

    public function subscribers(): BelongsToMany
    {
        return $this->belongsToMany(NewsletterSubscriber::class, 'list_subscriber', 'list_id', 'subscriber_id');
    }

    public function activeSubscribers(): BelongsToMany
    {
        return $this->subscribers()->where('status', 'active');
    }

    public function campaigns(): BelongsToMany
    {
        return $this->belongsToMany(NewsletterCampaign::class, 'campaign_list', 'list_id', 'campaign_id');
    }
}
