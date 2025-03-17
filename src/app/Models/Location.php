<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'appointment_id',
        'type',
        'provider',
        'link',
        'phone',
        'name',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
    ];

    public function services()
    {
        return $this->belongsToMany(Service::class, 'location_service');
    }
}
