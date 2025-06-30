<?php

namespace App\Http\Requests\Booking;

use App\Models\Workspace;
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
        return [
            'workspace_id' => ['required', 'exists:workspaces,id'],
            'user_id' => ['required', 'exists:users,id'],
            'date' => ['required', 'date', 'after_or_equal:today'],
            'time' => ['required', 'date_format:H:i'],
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => ['required', 'string'],
            'phone' => ['required', 'digits_between:8,15'],
            'services' => ['required', 'array'],
            'locations' => ['required', 'array'],
            'description' => ['nullable', 'string'],
        ];
    }

    public function prepareForValidation(): void
    {
        if ($this->workspace_id) {
            $workspace = Workspace::with('user')->find($this->workspace_id);
            if ($workspace && $workspace->user) {
                $this->merge([
                    'user_id' => $workspace->user->id,
                ]);
            }
        }
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Validation Error',
            'errors' => $validator->errors()
        ], 422));
    }
}
