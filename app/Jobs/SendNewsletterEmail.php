<?php

namespace App\Jobs;

use App\Mail\NewsletterMail;
use App\Models\NewsletterSendLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendNewsletterEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $backoff = 60;

    public function __construct(public readonly NewsletterSendLog $log) {}

    public function handle(): void
    {
        $log = $this->log->fresh(['subscriber', 'campaign']);

        if (! $log || $log->status === 'sent') {
            return;
        }

        if (! $log->subscriber?->isActive()) {
            $log->update(['status' => 'failed', 'error' => 'Subscriber is not active']);
            $this->decrementPending($log->campaign_id);

            return;
        }

        Mail::to($log->subscriber->email, $log->subscriber->name)
            ->send(new NewsletterMail($log->campaign, $log->subscriber));

        $log->update(['status' => 'sent', 'sent_at' => now()]);

        $log->campaign()->increment('sent_count');
        $this->checkCompletion($log->campaign_id);
    }

    public function failed(Throwable $exception): void
    {
        Log::error('Newsletter send failed', [
            'log_id' => $this->log->id,
            'subscriber_id' => $this->log->subscriber_id,
            'campaign_id' => $this->log->campaign_id,
            'error' => $exception->getMessage(),
        ]);

        $this->log->update([
            'status' => 'failed',
            'error' => mb_substr($exception->getMessage(), 0, 500),
        ]);

        $this->log->campaign()->increment('failed_count');
        $this->checkCompletion($this->log->campaign_id);
    }

    private function checkCompletion(int $campaignId): void
    {
        $campaign = \App\Models\NewsletterCampaign::find($campaignId);
        if (! $campaign || $campaign->status !== 'processing') {
            return;
        }

        $processed = $campaign->sent_count + $campaign->failed_count;
        if ($processed >= $campaign->total_recipients) {
            $campaign->update(['status' => 'completed']);
        }
    }

    private function decrementPending(int $campaignId): void
    {
        \App\Models\NewsletterCampaign::where('id', $campaignId)->decrement('total_recipients');
        $this->checkCompletion($campaignId);
    }
}
