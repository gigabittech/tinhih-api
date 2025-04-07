<?php

namespace App\Http\Requests\Invoice;

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
            'workspace_id' => $this->user()->currentWorkspace()->id,
            "issue_date" => now(),
            "due_date" => now()->addDays(7),
            "payable_amount" => 0,
            "is_paid" => false,
        ]);

        return [
            'workspace_id' => 'required|exists:workspaces,id',
            'title' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'po_so_number' => 'nullable|string|max:255',
            'tax_id' => 'nullable|exists:taxes,id',
            'issue_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:issue_date',
            'description' => 'nullable|string',
            'client_id' => 'required|exists:users,id',
            'biller_id' => 'required|exists:users,id',
            'payable_amount' => 'nullable|numeric|min:0',
            'is_paid' => 'nullable|boolean',
            'services' => 'required|array',
        ];
    }

    public function messages(): array
    {
        return [
            'workspace_id.required' => 'The workspace ID is required.',
            'workspace_id.exists' => 'The selected workspace ID is invalid.',
            'title.required' => 'The title is required.',
            'client_id.required' => 'Must have at least 1 item.',
            'biller_id.required' => 'The is required.',
            'serial_number.required' => 'The serial number is required.',
            'issue_date.required' => 'The issue date is required.',
            'due_date.required' => 'The due date is required.',
            'payable_amount.required' => 'The payable amount is required.',
            'payable_amount.numeric' => 'The payable amount must be a number.',
            'payable_amount.min' => 'The payable amount must be at least 0.',
            'is_paid.boolean' => 'The is paid field must be true or false.',
            'services.required' => 'Must have at least 1 item.',
        ];
    }
}
