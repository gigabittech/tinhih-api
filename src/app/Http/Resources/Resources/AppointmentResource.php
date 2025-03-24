<?php

namespace App\Http\Resources\Resources;

use App\Http\Resources\Location\LocationResource;
use App\Http\Resources\Service\ServiceResource;
use App\Http\Resources\TeamMemberResource;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\WorkspaceResource;
use App\Repository\ServiceRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "date" => $this->date,
            "time" => $this->time,
            "status" => $this->status,
            "locations" => LocationResource::collection($this->locations),
            "services" => ServiceResource::collection($this->services),
            "attendees" => $this->attendees
        ];
    }
}
