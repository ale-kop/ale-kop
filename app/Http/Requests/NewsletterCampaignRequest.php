<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewsletterCampaignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:200'],
            'subject' => ['required', 'string', 'max:200'],
            'content' => ['required', 'string'],
            'list_ids' => ['required', 'array', 'min:1'],
            'list_ids.*' => ['exists:newsletter_lists,id'],
            'scheduled_at' => ['nullable', 'date', 'after:now'],
        ];
    }

    public function messages(): array
    {
        return [
            'list_ids.required' => 'Selecione pelo menos uma lista.',
            'scheduled_at.after' => 'A data de agendamento deve ser futura.',
        ];
    }
}
