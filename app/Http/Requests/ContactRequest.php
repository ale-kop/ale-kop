<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'    => ['required', 'string', 'max:100'],
            'email'   => ['required', 'email:rfc,dns', 'max:254'],
            //'subject' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'min:10', 'max:3000'],
            'website' => ['nullable', 'max:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'    => 'Informe seu nome.',
            'email.required'   => 'O e-mail é obrigatório.',
            'email.email'      => 'Informe um e-mail válido.',
            'subject.required' => 'Informe o assunto.',
            'message.required' => 'Escreva sua mensagem.',
            'message.min'      => 'A mensagem precisa ter ao menos 10 caracteres.',
            'website.max'      => 'Envio inválido.',
        ];
    }
}
