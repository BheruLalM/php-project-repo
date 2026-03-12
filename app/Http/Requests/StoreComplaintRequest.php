<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreComplaintRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'complainant_name'    => ['required', 'string', 'max:255'],
            'contact'             => ['required', 'string', 'max:255'],
            'statement'           => ['required', 'string'],
            'status'              => ['required', 'in:open,under_investigation,closed'],
            'assigned_officer_id' => ['nullable', 'exists:users,id'],
            'crime_record_id'     => ['nullable', 'exists:crime_records,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'complainant_name.required' => 'Complainant name is required.',
            'contact.required'          => 'Contact information is required.',
            'statement.required'        => 'Statement is required.',
            'status.in'                 => 'Status must be: open, under_investigation, or closed.',
            'crime_record_id.exists'    => 'The linked case does not exist.',
            'assigned_officer_id.exists'=> 'The assigned officer does not exist.',
        ];
    }
}
