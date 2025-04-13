<?php

namespace App\Http\Requests\Workspace;

use App\Traits\AuthAvatarTrait;
use Illuminate\Foundation\Http\FormRequest;

class SetupRequest extends FormRequest
{
    use AuthAvatarTrait;
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
        $fullname = $this->first_name . ' ' . $this->last_name;
        $this->merge([
            'preferred_name' => $this->first_name,
            'full_name' => $fullname,
            'active' => true,
            'avatar' => $this->avatar($fullname)
        ]);

        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'profession' => 'required|string|max:255',
            'countryCode' => 'required|string|max:255',
            'teamSize' => 'required|in:justMe,inTen,moreThanTen',
            'timeZone' => 'nullable|string|max:255',
            'businessName' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'preferred_name' => 'required|string|max:255',
            'active' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'First name is required',
            'last_name.required' => 'Last name is required',
            'profession.required' => 'Profession is required',
            'countryCode.required' => 'Country code is required',
            'teamSize.required' => 'Team size is required',
            'teamSize.in' => 'Team size must be one of the following: justMe, inTen, moreThanTen',
            'timeZone.string' => 'Time zone must be a string',
            'businessName.required' => 'Business name is required',
        ];
    }
}
