<?php

namespace App\Http\Requests\Client;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

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
            'workspace_id' => $this->user()->workspaces()->where('active', 1)->first()->id
        ]);

        return [
            'workspace_id' => ['required'],
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required', 'email', 'unique:clients,email'],
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
        ], 422));
    }
}
