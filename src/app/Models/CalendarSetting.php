<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarSetting extends Model
{
    protected $fillable = [
        "workspace_id","week_start","show_weekends","timeslot","time_increment","time_format",
    ];

    public function workspace(){
        return $this->belongsTo(Workspace::class);
    }
}
