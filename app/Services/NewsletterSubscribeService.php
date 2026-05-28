<?php

namespace App\Services;

use App\Models\NewsletterList;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewsletterSubscribeService
{
    public function subscribe(array $data, ?string $listSlug = 'newsletter'): NewsletterSubscriber
    {
        return DB::transaction(function () use ($data, $listSlug) {
            $existing = NewsletterSubscriber::where('email', $data['email'])->first();

            if ($existing) {
                $this->updateMetadata($existing, $data);
                $subscriber = $existing;
            } else {
                $subscriber = NewsletterSubscriber::create([
                    'name' => $data['name'] ?? null,
                    'email' => $data['email'],
                    'ip_address' => $data['ip_address'] ?? null,
                    'status' => 'active',
                    'metadata' => $this->buildMetadata($data),
                ]);
            }

            if ($listSlug) {
                $list = NewsletterList::where('slug', $listSlug)->first();
                if ($list && ! $subscriber->lists()->where('list_id', $list->id)->exists()) {
                    $subscriber->lists()->attach($list->id);
                }
            }

            return $subscriber;
        });
    }

    private function updateMetadata(NewsletterSubscriber $subscriber, array $data): void
    {
        $existing = $subscriber->metadata ?? [];
        $new = $this->buildMetadata($data);

        $merged = array_merge($existing, array_filter($new, fn ($v) => $v !== null));

        $subscriber->update([
            'metadata' => $merged,
            'name' => $data['name'] ?? $subscriber->name,
        ]);

        if ($subscriber->status !== 'active') {
            $subscriber->update(['status' => 'active', 'subscribed_at' => now(), 'unsubscribed_at' => null]);
        }
    }

    private function buildMetadata(array $data): array
    {
        return array_filter([
            'source_url' => $data['source_url'] ?? null,
            'user_agent' => $data['user_agent'] ?? null,
            'utm_source' => $data['utm_source'] ?? null,
            'utm_medium' => $data['utm_medium'] ?? null,
            'utm_campaign' => $data['utm_campaign'] ?? null,
            'utm_content' => $data['utm_content'] ?? null,
            'utm_term' => $data['utm_term'] ?? null,
        ], fn ($v) => $v !== null);
    }

    public static function extractFromRequest(Request $request): array
    {
        return [
            'ip_address' => $request->ip(),
            'source_url' => $request->input('source_url', $request->headers->get('referer')),
            'user_agent' => $request->userAgent(),
            'utm_source' => $request->input('utm_source'),
            'utm_medium' => $request->input('utm_medium'),
            'utm_campaign' => $request->input('utm_campaign'),
            'utm_content' => $request->input('utm_content'),
            'utm_term' => $request->input('utm_term'),
        ];
    }
}
