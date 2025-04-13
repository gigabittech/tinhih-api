<?php

namespace App\Http\Resources;

use App\Http\Resources\Client\ClientResource;
use App\Http\Resources\Location\LocationResource;
use App\Http\Resources\Resources\AppointmentResource;
use App\Http\Resources\Service\ServiceResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkspaceResource extends JsonResource
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
            "active" => $this->active,
            "businessName" => $this->businessName,
            "countryCode" => $this->countryCode,
            'website' => $this->website,
            "locations" => LocationResource::collection($this->locations),
            "appointments" => AppointmentResource::collection($this->appointments),
            'clients' => ClientResource::collection($this->clients),
            'invoices' => InvoiceResource::collection($this->invoices),
            "members" => TeamMemberResource::collection($this->teamMembers),
            "services" => ServiceResource::collection($this->services),
            'taxes' => TaxResource::collection($this->taxs),
        ];
    }
}
