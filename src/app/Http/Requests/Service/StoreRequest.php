<?php

namespace App\Http\Requests\Service;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
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
            'workspace_id' => ['required', 'exists:workspaces,id'],
            'service_name' => ['required', 'string', 'max:255'],
            'display_name' => ['nullable', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:50', 'unique:services,code'],
            'duration' => ['nullable', 'integer', 'min:1'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'group_event' => ['nullable', 'boolean'],
            'max_attendees' => ['required_if:group_event,true', 'nullable', 'integer', 'min:1'],
            'taxable' => ['nullable', 'boolean'],
            'bookable_online' => ['nullable', 'boolean'],
            'allow_new_clients' => ['nullable', 'boolean'],
            'team_members' => ['nullable', 'array'],
            'team_members.*' => ['exists:users,id'],
            'locations' => ['required', 'array'],
            'locations.*' => ['exists:locations,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'service_name.required' => 'The service name is required.',
            'service_name.string' => 'The service name must be a valid string.',
            'service_name.max' => 'The service name must not exceed 255 characters.',

            'display_name.string' => 'The display name must be a valid string.',
            'display_name.max' => 'The display name must not exceed 255 characters.',

            'code.string' => 'The code must be a valid string.',
            'code.max' => 'The code must not exceed 50 characters.',
            'code.unique' => 'The code must be unique.',

            'duration.integer' => 'The duration must be a valid integer.',
            'duration.min' => 'The duration must be at least 1.',

            'price.numeric' => 'The price must be a valid number.',
            'price.min' => 'The price must be at least 0.',

            'description.string' => 'The description must be a valid string.',

            'group_event.boolean' => 'The group event must be a boolean value.',

            'max_attendees.integer' => 'The maximum attendees must be a valid integer.',
            'max_attendees.min' => 'The maximum attendees must be at least 1.',

            'taxable.boolean' => 'The taxable option must be a boolean value.',

            'bookable_online.boolean' => 'The bookable online option must be a boolean value.',

            'allow_new_clients.boolean' => 'The allow new clients option must be a boolean value.',

            'team_members.array' => 'The team members must be a valid array.',
            'team_members.*.exists' => 'Invalid team member(s).',

            'locations.required' => 'At least one location is required.',
            'locations.array' => 'The locations must be a valid array.',
            'locations.*.exists' => 'Invalid location(s).',
        ];
    }

    /**
     * Handle failed validation
     *
     * @param  Validator  $validator
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Validation Error',
            'errors' => $validator->errors()
        ], 422));
    }
}
