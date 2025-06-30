<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'user_id',
        'workspace_id',
        'date',
        'time',
        'description'
    ];


    public function locations()
    {
        return $this->belongsToMany(Location::class, 'appointment_location');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'appointment_service');
    }

    public function attendees()
    {
        return $this->belongsToMany(User::class, 'appointment_attendee');
    }
}
