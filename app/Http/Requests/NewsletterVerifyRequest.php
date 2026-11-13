<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class NewsletterVerifyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (is_string($this->email)) {
            $this->merge(['email' => Str::lower(trim($this->email))]);
        }
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:254'],
            'code' => ['required', 'digits:6'],
            'list' => ['nullable', 'string', 'max:100'],
            // Honeypot anti-spam field (must be empty)
            'website' => ['nullable', 'max:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Digite o código de 6 dígitos.',
            'code.digits' => 'O código tem 6 dígitos.',
        ];
    }
}
