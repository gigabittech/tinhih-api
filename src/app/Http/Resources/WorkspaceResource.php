<?php

namespace App\Http\Resources;

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
            "name" => $this->name,
            "country" => $this->country,
            "profession" => $this->profession,
            'url' => $this->url,
            "locations" => LocationResource::collection($this->locations),
            "members" => TeamMemberResource::collection($this->teamMembers),
            "services" => ServiceResource::collection($this->services),
            "appointments" => AppointmentResource::collection($this->appointments),
        ];
    }
}
