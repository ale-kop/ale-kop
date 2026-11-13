<?php

namespace App\Services;

use App\Exceptions\NewsletterCodeCooldownException;
use App\Mail\NewsletterVerificationMail;
use App\Models\NewsletterSubscriber;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

/** Dupla verificação do e-mail por código de 6 dígitos (guardado só como hash). */
class NewsletterVerificationService
{
    public const CODE_TTL_MINUTES = 15;

    public const RESEND_COOLDOWN_SECONDS = 60;

    public const MAX_ATTEMPTS = 5;

    /** Gera um código novo (invalidando o anterior) e o envia pela fila. */
    public function sendCode(NewsletterSubscriber $subscriber): void
    {
        $wait = $this->secondsUntilNextSend($subscriber);
        if ($wait > 0) {
            throw new NewsletterCodeCooldownException($wait);
        }

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $subscriber->update([
            'verification_code' => Hash::make($code),
            'verification_expires_at' => now()->addMinutes(self::CODE_TTL_MINUTES),
            'verification_sent_at' => now(),
            'verification_attempts' => 0,
        ]);

        Mail::to($subscriber->email)->queue(new NewsletterVerificationMail($code, self::CODE_TTL_MINUTES));
    }

    /**
     * Confere o código. Erros contam como tentativa; ao chegar no limite o código
     * deixa de valer e é preciso pedir outro. Chamar dentro de transação com lock.
     */
    public function check(NewsletterSubscriber $subscriber, string $code): bool
    {
        if (! $subscriber->verification_code
            || ! $subscriber->verification_expires_at
            || $subscriber->verification_expires_at->isPast()
            || $subscriber->verification_attempts >= self::MAX_ATTEMPTS) {
            return false;
        }

        if (! Hash::check($code, $subscriber->verification_code)) {
            $subscriber->increment('verification_attempts');

            return false;
        }

        $subscriber->update([
            'verification_code' => null,
            'verification_expires_at' => null,
            'verification_attempts' => 0,
        ]);

        return true;
    }

    private function secondsUntilNextSend(NewsletterSubscriber $subscriber): int
    {
        if (! $subscriber->verification_sent_at) {
            return 0;
        }

        $nextAt = $subscriber->verification_sent_at->timestamp + self::RESEND_COOLDOWN_SECONDS;

        return max(0, $nextAt - now()->timestamp);
    }
}
