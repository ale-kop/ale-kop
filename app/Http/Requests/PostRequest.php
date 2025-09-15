<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
            'content' => ['nullable', 'string'],
            'meta' => ['nullable', 'array'],
            'extra' => ['nullable', 'array'],
            'tag_id' => ['nullable', 'exists:tags,id'],
            'section_id' => ['nullable', 'exists:sections,id'],
            'featured_image' => ['nullable', 'file', 'image'],
        ];
    }
}
