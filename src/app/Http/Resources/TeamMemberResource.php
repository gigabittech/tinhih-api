<?php

namespace App\Http\Resources;

use App\Http\Resources\Service\ServiceResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamMemberResource extends JsonResource
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
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'job_title' => $this->job_title,
            'npi' => $this->npi,
            'avatar' => $this->avatar,
            'texonomy' => $this->texonomy,
            'workspace_id' => $this->workspace->id,
        ];
    }
}
