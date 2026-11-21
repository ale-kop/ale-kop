<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class NewsletterList extends Model
{
    /** Lista geral: toda inscrição confirmada entra nela, venha de qual formulário vier. */
    public const DEFAULT_SLUG = 'newsletter';

    protected $guarded = [];

    public function subscribers(): BelongsToMany
    {
        return $this->belongsToMany(NewsletterSubscriber::class, 'list_subscriber', 'list_id', 'subscriber_id');
    }

    public function activeSubscribers(): BelongsToMany
    {
        return $this->subscribers()->where('status', 'active');
    }

    /** Acrescenta active_subscribers_count (só quem recebe campanhas). */
    public function scopeWithActiveCount($query)
    {
        return $query->withCount(['subscribers as active_subscribers_count' => fn ($q) => $q->where('status', 'active')]);
    }

    public function campaigns(): BelongsToMany
    {
        return $this->belongsToMany(NewsletterCampaign::class, 'campaign_list', 'list_id', 'campaign_id');
    }
}
