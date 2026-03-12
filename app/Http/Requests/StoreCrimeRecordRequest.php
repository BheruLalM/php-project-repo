<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCrimeRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'crime_type'          => ['required', 'string', 'max:255'],
            'description'         => ['required', 'string'],
            'location'            => ['required', 'string', 'max:255'],
            'date_of_occurrence'  => ['required', 'date', 'before_or_equal:today'],
            'status'              => ['required', 'in:open,under_investigation,closed'],
            'criminal_id'         => ['nullable', 'exists:criminals,id'],
            'assigned_officer_id' => ['nullable', 'exists:users,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'crime_type.required'         => 'Crime type is required.',
            'date_of_occurrence.required' => 'Date of occurrence is required.',
            'date_of_occurrence.before_or_equal' => 'Date of occurrence cannot be in the future.',
            'status.in'                   => 'Status must be: open, under_investigation, or closed.',
            'criminal_id.exists'          => 'Selected criminal does not exist.',
            'assigned_officer_id.exists'  => 'Selected officer does not exist.',
        ];
    }
}
