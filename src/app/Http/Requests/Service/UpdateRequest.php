<?php

namespace App\Http\Requests\Service;

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
            'service_name' => ['sometimes', 'string', 'max:255'],
            'display_name' => ['sometimes', 'string', 'max:255'],
            'code' => ['sometimes', 'string', 'max:50', Rule::unique('services', 'code')->ignore($this->route('id'))],
            'duration' => ['sometimes', 'integer', 'min:1'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'group_event' => ['sometimes', 'boolean'],
            'max_attendees' => ['nullable', 'integer', 'min:1'],
            'taxable' => ['sometimes', 'boolean'],
            'bookable_online' => ['sometimes', 'boolean'],
            'allow_new_clients' => ['sometimes', 'boolean'],
            'team_members' => ['nullable', 'array'],
            'team_members.*' => ['exists:users,id'],
            'locations' => ['required', 'array'],
            'locations.*' => ['exists:locations,id'],
        ];
    }

    public function messages()
    {
        return [
            'service_name.sometimes' => 'The service name is optional, but if provided, it must be a valid string.',
            'service_name.string' => 'The service name must be a valid string.',
            'service_name.max' => 'The service name must not exceed 255 characters.',

            'display_name.sometimes' => 'The display name is optional, but if provided, it must be a valid string.',
            'display_name.string' => 'The display name must be a valid string.',
            'display_name.max' => 'The display name must not exceed 255 characters.',

            'code.sometimes' => 'The code is optional, but if provided, it must be a valid string.',
            'code.string' => 'The code must be a valid string.',
            'code.max' => 'The code must not exceed 50 characters.',
            'code.unique' => 'The code must be unique, and the selected code is already taken.',

            'duration.sometimes' => 'The duration is optional, but if provided, it must be a valid integer.',
            'duration.integer' => 'The duration must be a valid integer.',
            'duration.min' => 'The duration must be at least 1.',

            'price.sometimes' => 'The price is optional, but if provided, it must be a valid number.',
            'price.numeric' => 'The price must be a valid number.',
            'price.min' => 'The price must be at least 0.',

            'description.nullable' => 'The description is optional, but if provided, it must be a valid string.',

            'group_event.sometimes' => 'The group event is optional, but if provided, it must be a boolean value.',
            'group_event.boolean' => 'The group event must be a boolean value.',

            'max_attendees.nullable' => 'The maximum attendees are optional, but if provided, it must be a valid integer.',
            'max_attendees.integer' => 'The maximum attendees must be a valid integer.',
            'max_attendees.min' => 'The maximum attendees must be at least 1.',

            'taxable.sometimes' => 'The taxable option is optional, but if provided, it must be a boolean value.',
            'taxable.boolean' => 'The taxable option must be a boolean value.',

            'bookable_online.sometimes' => 'The bookable online option is optional, but if provided, it must be a boolean value.',
            'bookable_online.boolean' => 'The bookable online option must be a boolean value.',

            'allow_new_clients.sometimes' => 'The allow new clients option is optional, but if provided, it must be a boolean value.',
            'allow_new_clients.boolean' => 'The allow new clients option must be a boolean value.',

            'team_members.nullable' => 'The team members are optional, but if provided, it must be a valid array.',
            'team_members.array' => 'The team members must be a valid array.',
            'team_members.*.exists' => 'Each team member must exist in the users table.',

            'locations.required' => 'At least one location is required.',
            'locations.array' => 'The locations must be a valid array.',
            'locations.*.exists' => 'Invalid location(s).',
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
