<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
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
            'featured_image' => ['nullable', 'file', 'image'],
        ];
    }
}
