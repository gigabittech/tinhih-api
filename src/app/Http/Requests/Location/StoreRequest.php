<?php

namespace App\Http\Requests\Location;

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
            'workspace_id' => $this->user()->workspaces()->where('active', true)->first()->id,
            'user_id' => $this->user()->id,
        ]);

        return [
            'workspace_id' => 'required|exists:workspaces,id',
            'type_id' => 'required|exists:location_types,id',
            'phone' => 'nullable|string',
            'address' => 'nullable',
            'link' => 'nullable|url',
            'display_name' => 'required|string',
            'city' => 'nullable',
            'state' => 'nullable',
            'zip_code' => 'nullable',
            'country' => 'nullable',
            'user_id' => 'required'
        ];
    }


    public function messages()
    {
        return [
            'type_id.required' => 'The location type is required.',
            'type_id.exists' => 'The location type must be a valid location type.',
            'user_id.required' => 'The user is required.',
            'user_id.exists' => 'The user must be a valid user.',
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
