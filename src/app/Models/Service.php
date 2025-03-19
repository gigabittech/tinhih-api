<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'user_id',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Many-to-Many Relationship with Users (Team Members)
    public function teamMembers()
    {
        return $this->belongsToMany(User::class, 'service_team_member');
    }

    // Many-to-Many Relationship with Locations
    public function locations()
    {
        return $this->belongsToMany(Location::class, 'service_location');
    }
}
