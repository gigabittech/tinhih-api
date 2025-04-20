<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CalendarSettingResource extends JsonResource
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
            'week_start' => $this->week_start,
            'show_weekends' => $this->show_weekends,
            'timeslot_size' => $this->timeslot_size,
            'time_increment' => $this->time_increment,
            'time_format' => $this->time_format
        ];
    }
}
