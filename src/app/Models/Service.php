<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'service_name',
        'display_name',
        'code',
        'duration',
        'price',
        'description',
        'group_event',
        'max_attendees',
        'taxable',
        'bookable_online',
        'allow_new_clients'
    ];

    // Many-to-Many Relationship with Users (Team Members)
    public function teamMembers()
    {
        return $this->belongsToMany(User::class, 'service_user');
    }

    // Many-to-Many Relationship with Locations
    public function locations()
    {
        return $this->belongsToMany(Location::class, 'location_service');
    }
}
