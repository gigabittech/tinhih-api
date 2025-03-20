<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'date',
        'time',
        'description'
    ];


    public function locations()
    {
        return $this->belongsTo(Location::class, 'appointment_location');
    }

    public function services()
    {
        return $this->belongsTo(Service::class, 'appointment_service');
    }

    public function attendees()
    {
        return $this->belongsTo(User::class, 'appointment_attendee');
    }
}
