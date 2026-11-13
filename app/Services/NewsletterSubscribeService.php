<?php

namespace App\Services;

use App\Models\NewsletterList;
use App\Models\NewsletterSubscriber;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Inscrição em duas etapas: subscribe() registra o e-mail como "pending" e envia o código;
 * verify() confere o código, ativa o inscrito e o coloca na lista.
 */
class NewsletterSubscribeService
{
    public function __construct(private readonly NewsletterVerificationService $verification) {}

    public function subscribe(array $data): NewsletterSubscriber
    {
        $subscriber = DB::transaction(function () use ($data) {
            $existing = NewsletterSubscriber::where('email', $data['email'])->lockForUpdate()->first();

            if ($existing) {
                $this->refreshExisting($existing, $data);

                return $existing;
            }

            return NewsletterSubscriber::create([
                'name' => $data['name'] ?? null,
                'email' => $data['email'],
                'ip_address' => $data['ip_address'] ?? null,
                'status' => 'pending',
                'source_path' => $data['source_path'] ?? null,
                'source_form' => $data['source_form'] ?? null,
                'source_post_id' => $data['source_post_id'] ?? null,
                'metadata' => $this->buildMetadata($data),
            ]);
        });

        // Pode lançar NewsletterCodeCooldownException (reenvio rápido demais).
        $this->verification->sendCode($subscriber);

        return $subscriber;
    }

    /** Confere o código; em caso de acerto ativa o inscrito e o coloca na lista. */
    public function verify(string $email, string $code, ?string $listSlug = 'newsletter'): bool
    {
        return DB::transaction(function () use ($email, $code, $listSlug) {
            $subscriber = NewsletterSubscriber::where('email', $email)->lockForUpdate()->first();

            if (! $subscriber || ! $this->verification->check($subscriber, $code)) {
                return false;
            }

            $attributes = ['email_verified_at' => now()];
            if ($subscriber->status !== 'active') {
                // pending, descadastrado ou bounced: a confirmação (re)ativa
                $attributes += ['status' => 'active', 'subscribed_at' => now(), 'unsubscribed_at' => null];
            }
            $subscriber->update($attributes);

            $list = $listSlug ? NewsletterList::where('slug', $listSlug)->first() : null;
            if ($list && ! $subscriber->lists()->where('list_id', $list->id)->exists()) {
                $subscriber->lists()->attach($list->id);
            }

            return true;
        });
    }

    /** Quem já está inscrito mantém o status; a origem do cadastro é a primeira (first-touch). */
    private function refreshExisting(NewsletterSubscriber $subscriber, array $data): void
    {
        $existing = $subscriber->metadata ?? [];
        $new = $this->buildMetadata($data);

        $metadata = array_merge($existing, $new);
        if (isset($existing['source_url'])) {
            $metadata['source_url'] = $existing['source_url'];
        }

        $attributes = ['metadata' => $metadata, 'name' => $data['name'] ?? $subscriber->name];

        if ($subscriber->source_path === null) {
            $attributes += [
                'source_path' => $data['source_path'] ?? null,
                'source_form' => $data['source_form'] ?? null,
                'source_post_id' => $data['source_post_id'] ?? null,
            ];
        }

        $subscriber->update($attributes);
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
        $sourceUrl = $request->input('source_url', $request->headers->get('referer'));
        $sourcePath = self::pathOf($sourceUrl, $request);

        return [
            'ip_address' => $request->ip(),
            'source_url' => $sourceUrl,
            'source_path' => $sourcePath,
            'source_form' => $request->input('source_form'),
            'source_post_id' => self::postIdOf($sourcePath),
            'user_agent' => $request->userAgent(),
            'utm_source' => $request->input('utm_source'),
            'utm_medium' => $request->input('utm_medium'),
            'utm_campaign' => $request->input('utm_campaign'),
            'utm_content' => $request->input('utm_content'),
            'utm_term' => $request->input('utm_term'),
        ];
    }

    /** Caminho normalizado da página ("/slug", sem host, query ou barra final). Outro domínio é ignorado. */
    private static function pathOf(?string $url, Request $request): ?string
    {
        $parts = $url ? parse_url($url) : false;

        if (! $parts || (isset($parts['host']) && strcasecmp($parts['host'], $request->getHost()) !== 0)) {
            return null;
        }

        $path = '/'.trim($parts['path'] ?? '', '/');

        return mb_substr($path, 0, 255);
    }

    /** Posts (avulsos e aulas) vivem em "/{slug}"; as demais páginas não têm post. */
    private static function postIdOf(?string $path): ?int
    {
        $slug = $path !== null ? ltrim($path, '/') : '';

        if ($slug === '' || str_contains($slug, '/')) {
            return null;
        }

        return Post::where('slug', $slug)->value('id');
    }
}
