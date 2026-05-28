<?php

namespace App\Jobs;

use App\Models\NewsletterCampaign;
use App\Models\NewsletterSendLog;
use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ProcessNewsletterCampaign implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 3600;

    public int $tries = 1;

    public function __construct(public readonly NewsletterCampaign $campaign) {}

    public function handle(): void
    {
        if (! in_array($this->campaign->status, ['scheduled', 'processing'])) {
            return;
        }

        $this->campaign->update(['status' => 'processing', 'sent_at' => now()]);

        $subscriberIds = NewsletterSubscriber::active()
            ->whereHas('lists', fn ($q) => $q->whereIn('newsletter_lists.id', $this->campaign->lists->pluck('id')))
            ->pluck('id')
            ->unique();

        DB::transaction(function () use ($subscriberIds) {
            $logs = $subscriberIds->map(fn ($id) => [
                'campaign_id' => $this->campaign->id,
                'subscriber_id' => $id,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ])->all();

            // insertOrIgnore prevents duplicates on re-processing
            NewsletterSendLog::insertOrIgnore($logs);

            $this->campaign->update(['total_recipients' => $subscriberIds->count()]);
        });

        // Dispatch individual send jobs in chunks with delay between batches
        $chunkIndex = 0;
        NewsletterSendLog::where('campaign_id', $this->campaign->id)
            ->where('status', 'pending')
            ->with('subscriber')
            ->chunkById(50, function ($chunk) use (&$chunkIndex) {
                $delaySeconds = $chunkIndex * 60;
                foreach ($chunk as $log) {
                    SendNewsletterEmail::dispatch($log)
                        ->onQueue('newsletter')
                        ->delay(now()->addSeconds($delaySeconds));
                }
                $chunkIndex++;
            });
    }
}
