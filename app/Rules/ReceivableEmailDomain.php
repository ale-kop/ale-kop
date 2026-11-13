<?php

namespace App\Rules;

use App\Services\EmailDomainChecker;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

/** Troca o `email:dns`: reprova só domínio que não existe, nunca por falha passageira de DNS. */
class ReceivableEmailDomain implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $domain = Str::afterLast((string) $value, '@');

        if (! app(EmailDomainChecker::class)->isAcceptable($domain)) {
            $fail("Não encontramos o domínio {$domain}. Confira se o e-mail está certo.");
        }
    }
}
