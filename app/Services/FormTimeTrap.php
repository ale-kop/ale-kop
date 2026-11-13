<?php

namespace App\Services;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

/**
 * Anti-spam por tempo: o formulário carrega um token com a hora em que foi gerado
 * e o envio só vale se passou tempo suficiente para uma pessoa ter preenchido.
 * Pega scripts que baixam a página e postam na hora (ou postam sem o token).
 */
class FormTimeTrap
{
    public const MIN_SECONDS = 3;

    /** Token assinado (criptografado) com o instante atual. */
    public function issue(): string
    {
        return Crypt::encryptString((string) now()->getTimestamp());
    }

    public function passes(?string $token, int $minSeconds = self::MIN_SECONDS): bool
    {
        if (! is_string($token) || $token === '') {
            return false;
        }

        try {
            $issuedAt = (int) Crypt::decryptString($token);
        } catch (DecryptException) {
            return false;
        }

        $age = now()->getTimestamp() - $issuedAt;

        // age < 0: token do futuro, só pode ter sido forjado
        return $age >= $minSeconds;
    }
}
