<?php

namespace App\Http\Resources\Service;

use App\Http\Resources\Location\LocationResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
            'service_name' => $this->service_name,
            'display_name' => $this->display_name,
            'price' => $this->price,
            'duration' => $this->duration,
            'code' => $this->code,
            'description' => $this->description,
            'group_event' => $this->group_event,
            'max_attendees' => $this->max_attendees,
            'taxable' => $this->taxable,
            'bookable_online' => $this->bookable_online,
            'allow_new_clients' => $this->allow_new_clients,
            'locations' => LocationResource::collection($this->locations),
            'team_members' => UserResource::collection($this->teamMembers),
        ];
    }
}
