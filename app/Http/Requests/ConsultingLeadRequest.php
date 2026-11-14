<?php

namespace App\Http\Requests;

use App\Rules\ReceivableEmailDomain;
use Illuminate\Foundation\Http\FormRequest;

/** Formulário de triagem da página da consultoria. */
class ConsultingLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => mb_strtolower(trim((string) $this->input('email'))),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['bail', 'required', 'email:rfc', 'max:254', new ReceivableEmailDomain],
            'phone' => ['required', 'string', 'max:30', 'regex:/^[\d\s()+.-]{10,30}$/'],
            'business' => ['required', 'string', 'max:1000'],
            'problem' => ['required', 'string', 'min:10', 'max:3000'],
            'current_process' => ['required', 'string', 'max:3000'],
            'monthly_cost' => ['nullable', 'string', 'max:500'],
            'decision_maker' => ['required', 'string', 'max:200'],
            'website' => ['nullable', 'max:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Informe seu nome.',
            'email.required' => 'Informe seu e-mail.',
            'email.email' => 'Informe um e-mail válido.',
            'phone.required' => 'Informe seu WhatsApp para eu poder responder.',
            'phone.regex' => 'Informe o WhatsApp com DDD, só números.',
            'business.required' => 'Conte o que sua empresa faz e quantas pessoas trabalham nela.',
            'problem.required' => 'Conte qual problema você quer resolver.',
            'problem.min' => 'Conte um pouco mais sobre o problema.',
            'current_process.required' => 'Conte como isso é feito hoje.',
            'decision_maker.required' => 'Conte quem decide sobre isso na empresa.',
            'website.max' => 'Envio inválido.',
        ];
    }
}
