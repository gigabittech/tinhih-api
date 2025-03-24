<?php

namespace App\Http\Resources\Client;

use App\Http\Resources\Resources\AppointmentResource;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'status' => $this->status,
            'email' => $this->email,
            'phone' => $this->phone,
            'in' => $this->in,
            'dob' => $this->dob,
            'sex' => $this->sex,
            'relationship' => $this->relationship,
            'emp_status' => $this->emp_status,
            'ethnicity' => $this->ethnicity,
            'notes' => $this->ethnicity,
        ];
    }
}
