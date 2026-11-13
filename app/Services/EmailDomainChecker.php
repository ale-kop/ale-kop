<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

/**
 * Diz se o domínio de um e-mail existe (MX, A ou AAAA), sem derrubar e-mail válido
 * por falha passageira de DNS.
 *
 * O `email:dns` do Laravel trata qualquer erro de consulta como "e-mail inválido":
 * timeout ou cache frio do resolvedor na primeira tentativa reprovam um endereço
 * certo, e a segunda tentativa passa. Aqui:
 *  - acerto é guardado em cache por 1 dia (a maioria das tentativas nem consulta DNS);
 *  - se falhar, tenta de novo uma vez;
 *  - se ainda falhar, consulta um domínio que sempre existe: se nem ele resolve, o
 *    problema é o DNS e não o e-mail, então o endereço é aceito. Endereço digitado
 *    errado é pego pelo código de confirmação.
 */
class EmailDomainChecker
{
    public const CANARY_DOMAIN = 'gmail.com';

    private const CACHE_TTL_DAYS = 1;

    public function isAcceptable(string $domain): bool
    {
        $domain = $this->normalize($domain);

        if ($domain === '') {
            return false;
        }

        $cacheKey = "email-domain-ok:{$domain}";
        if (Cache::get($cacheKey)) {
            return true;
        }

        foreach ([1, 2] as $attempt) {
            if ($this->hasRecords($domain)) {
                Cache::put($cacheKey, true, now()->addDays(self::CACHE_TTL_DAYS));

                return true;
            }

            if ($attempt === 1) {
                $this->pauseBeforeRetry();
            }
        }

        // O domínio não resolveu: ele não existe ou o DNS está com problema?
        return ! $this->hasRecords(self::CANARY_DOMAIN);
    }

    protected function pauseBeforeRetry(): void
    {
        usleep(250_000);
    }

    /** true se existir algum registro MX, A ou AAAA. Falha de consulta conta como "sem registros". */
    protected function hasRecords(string $host): bool
    {
        foreach ([DNS_MX, DNS_A, DNS_AAAA] as $type) {
            $records = @dns_get_record($host, $type);

            if (is_array($records) && $records !== []) {
                return true;
            }
        }

        return false;
    }

    private function normalize(string $domain): string
    {
        $domain = rtrim(strtolower(trim($domain)), '.');

        if ($domain === '') {
            return '';
        }

        return (string) (idn_to_ascii($domain, IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46) ?: $domain);
    }
}
