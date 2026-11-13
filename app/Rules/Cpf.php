<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * CPF válido (dígitos verificadores). Aceita com ou sem pontuação.
 */
class Cpf implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cpf = preg_replace('/\D/', '', (string) $value);

        if (strlen($cpf) !== 11 || preg_match('/^(\d)\1{10}$/', $cpf)) {
            $fail('Informe um CPF válido.');

            return;
        }

        for ($length = 9; $length < 11; $length++) {
            $sum = 0;
            for ($i = 0; $i < $length; $i++) {
                $sum += (int) $cpf[$i] * (($length + 1) - $i);
            }

            if ((int) $cpf[$length] !== ((10 * $sum) % 11) % 10) {
                $fail('Informe um CPF válido.');

                return;
            }
        }
    }
}
