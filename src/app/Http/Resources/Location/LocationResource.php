<?php

namespace App\Http\Resources\Location;

use App\Http\Resources\LocationTypeResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
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
            'type' => new LocationTypeResource($this->locationType),
            'display_name' => $this->display_name,
        ] + ($this->locationType->type === 'phone' ? [
            'phone' => $this->phone,
        ] : []) + ($this->locationType->type === 'remote' ? [
            'link' => $this->link,
        ] : []) + ($this->locationType->type === 'person' ? [
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'zip_code' => $this->zip_code,
            'country' => $this->country,
        ] : []);
    }
}
