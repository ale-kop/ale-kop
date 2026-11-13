<?php

namespace App\Http\Requests;

use App\Rules\Cpf;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => mb_strtolower(trim((string) $this->input('email'))),
            'document' => preg_replace('/\D/', '', (string) $this->input('document')),
        ]);
    }

    public function rules(): array
    {
        $isCard = $this->input('payment_method') === 'card';

        return [
            'name' => ['required', 'string', 'min:3', 'max:120'],
            'email' => ['required', 'email', 'max:190'],
            'document' => ['required', new Cpf],
            'payment_method' => ['required', Rule::in(['card', 'pix'])],
            'token' => [Rule::requiredIf($isCard), 'nullable', 'string', 'max:255'],
            'payment_method_id' => [Rule::requiredIf($isCard), 'nullable', 'string', 'max:40'],
            'issuer_id' => ['nullable', 'string', 'max:40'],
            'installments' => [
                Rule::requiredIf($isCard), 'nullable', 'integer', 'min:1',
                'max:'.config('services.mercadopago.max_installments'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Informe seu nome completo.',
            'name.min' => 'Informe seu nome completo.',
            'email.required' => 'Informe seu e-mail.',
            'email.email' => 'Informe um e-mail válido.',
            'document.required' => 'Informe seu CPF.',
            'token.required' => 'Confira os dados do cartão.',
            'payment_method_id.required' => 'Confira o número do cartão.',
            'installments.required' => 'Escolha o número de parcelas.',
        ];
    }
}
