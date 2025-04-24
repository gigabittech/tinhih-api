<?php

namespace App\Http\Requests\Workspace;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;


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
        $this->user()->nutralWorkspaces();
        $this->merge([
            'user_id' => $this->user()->id,
            'active' => true,
        ]);
        return [
            'user_id' => ['required', 'exists:users,id'],
            'businessName' => ['required', 'string', 'max:255'],
            'countryCode' => ['required', 'string', 'max:255'],
            'profession' => ['required', 'string', 'max:255'],
            'active' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'User is required',
            'user_id.exists' => 'User does not exist',
            'businessName.required' => 'Business name is required',
            'countryCode.required' => 'Country is required',
            'profession.required' => 'Profession is required',
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
