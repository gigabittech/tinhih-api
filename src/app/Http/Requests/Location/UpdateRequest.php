<?php

namespace App\Http\Requests\Location;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => 'required|in:physical,video',
            'name' => 'required|string|max:255',

            // Physical Location
            'address' => 'nullable|required_if:type,physical|string',
            'city' => 'nullable|required_if:type,physical|string|max:100',
            'state' => 'nullable|required_if:type,physical|string|max:100',
            'zip_code' => 'nullable|required_if:type,physical|string|max:20',
            'country' => 'nullable|required_if:type,physical|string|max:100',

            // Video Platform
            'provider_name' => 'nullable|required_if:type,video|string|max:255',
            'logo' => 'nullable|url',
            'icon' => 'nullable|url',
            'link' => 'nullable|url',
        ];
    }
}
