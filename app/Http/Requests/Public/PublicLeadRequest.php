<?php

namespace App\Http\Requests\Public;

use Illuminate\Foundation\Http\FormRequest;

class PublicLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:190'],
            'phone' => ['nullable', 'string', 'max:40'],
            'company' => ['nullable', 'string', 'max:150'],
            'subject' => ['nullable', 'string', 'max:200'],
            'message' => ['required', 'string', 'max:5000'],
            'budget' => ['nullable', 'string', 'max:60'],
            'project_type' => ['nullable', 'string', 'max:120'],
            'service_id' => ['nullable', 'integer', 'exists:services,id'],
            // Honeypot: real users never see/fill this; bots do. Must stay empty.
            'website' => ['prohibited'],
        ];
    }

    public function attributes(): array
    {
        return ['service_id' => 'service'];
    }

    public function messages(): array
    {
        return ['website.prohibited' => 'Spam detected.'];
    }

    /** Only the real lead fields (drops the honeypot). */
    public function leadData(): array
    {
        return $this->safe()->except('website') + ['source' => 'contact_form'];
    }
}
