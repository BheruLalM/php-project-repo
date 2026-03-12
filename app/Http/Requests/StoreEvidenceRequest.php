<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEvidenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'crime_record_id' => ['required', 'exists:crime_records,id'],
            'file'            => ['required', 'file', 'mimes:jpeg,jpg,png,pdf', 'max:5120'],
            'description'     => ['nullable', 'string', 'max:500'],
            'file_type'       => ['required', 'in:image,pdf,other'],
        ];
    }

    public function messages(): array
    {
        return [
            'crime_record_id.required' => 'A crime record must be linked to this evidence.',
            'crime_record_id.exists'   => 'The linked crime record does not exist.',
            'file.required'            => 'An evidence file is required.',
            'file.mimes'               => 'Evidence must be a JPEG, PNG, or PDF file.',
            'file.max'                 => 'Evidence file must be under 5MB.',
            'file_type.in'             => 'File type must be: image, pdf, or other.',
        ];
    }
}
