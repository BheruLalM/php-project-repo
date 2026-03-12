<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCriminalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Authorization handled by Policy in controller
    }

    public function rules(): array
    {
        return [
            'full_name'        => ['required', 'string', 'max:255'],
            'alias'            => ['nullable', 'string', 'max:255'],
            'date_of_birth'    => ['nullable', 'date', 'before:today'],
            'physical_markers' => ['nullable', 'string', 'max:1000'],
            'nationality'      => ['nullable', 'string', 'max:100'],
            'address'          => ['nullable', 'string', 'max:500'],
            'status'           => ['required', 'in:wanted,arrested,released,deceased'],
            'photo'            => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required'   => 'The criminal\'s full name is required.',
            'status.in'            => 'Status must be: wanted, arrested, released, or deceased.',
            'photo.max'            => 'Mugshot photo must be under 2MB.',
            'date_of_birth.before' => 'Date of birth must be in the past.',
        ];
    }
}
