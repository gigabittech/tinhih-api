<?php

namespace App\Http\Requests\Client;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

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
            'first_name' => ['required'],
            'last_name' => ['required'],
            Rule::unique('clients', 'email')->ignore($this->user()->id),
            'status' => ['required'],
            'phone' => ['required'],
            'in' => ['nullable'],
            'dob' => ['nullable'],
            'sex' => ['nullable'],
            'relationship' => ['nullable'],
            'emp_status' => ['nullable'],
            'ethnicity' => ['nullable'],
            'notes' => ['nullable'],
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => "Validation error",
            "errors" => $validator->errors()
        ]));
    }
}
