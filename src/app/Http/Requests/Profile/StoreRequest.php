<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
        $this->merge([
            'preferred_name' => $this->first_name,
            'full_name' => $this->first_name . ' ' . $this->last_name,
        ]);
        return [
            'first_name' => 'nullable|required_with:last_name|string|max:255',
            'last_name' => 'nullable|required_with:first_name|string|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'dob' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:255',
            'avatar' => 'nullable|string|max:255',
            'locale' => 'nullable|string',
            'time_zone' => 'nullable'
        ];
    }
}
