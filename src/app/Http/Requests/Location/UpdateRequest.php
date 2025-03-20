<?php

namespace App\Http\Requests\Location;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

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
            'display_name' => 'required|string',
            'phone' => 'nullable|string',
            'address' => 'nullable',
            'link' => 'nullable|url',
            'city' => 'nullable',
            'state' => 'nullable',
            'zip_code' => 'nullable',
            'country' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'phone.string' => 'The phone must be a valid string.',
            'link.url' => 'The link must be a valid URL.',
            'display_name.required' => 'The display name is required.',
            'display_name.string' => 'The display name must be a valid string.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Validation Error',
            'errors' => $validator->errors()
        ], 422));
    }
}
