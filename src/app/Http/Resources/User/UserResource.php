<?php

namespace App\Http\Resources\User;

use App\Http\Resources\WorkspaceResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'role' => $this->role,
            'avatar' => $this->avatar,
            'email' => $this->email,
            'first_name' => $this->profile?->first_name,
            'last_name' => $this->profile?->last_name,
            'full_name' => $this->profile?->full_name,
            'preferred_name' => $this->profile?->preferred_name,
            'phone' => $this->profile?->phone,
            'gender' => $this->profile?->gender,
            'note' => $this->profile?->note,
            'locale' => $this->profile?->locale,
            'time_zone' => $this->profile?->time_zone,
            'workspaces' => $this->workspaces()->where('active', false)->get(),
            'currentWorkspace' => new WorkspaceResource($this->currentWorkspace() ?? null)
        ];
    }
}
