<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'meta' => ['nullable', 'array'],
            'extra' => ['nullable', 'array'],
            'extra.color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'featured_image' => ['nullable', 'file', 'image'],
            // 0 = manter, 1 = remover imagem existente
            'featured_image_remove' => ['nullable', 'in:0,1'],
        ];
    }
}
