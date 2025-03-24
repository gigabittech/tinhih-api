<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'workspace_id',
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

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }

    // Many-to-Many Relationship with Users (Team Members)
    public function teamMembers()
    {
        return $this->belongsToMany(TeamMember::class, 'service_team_member');
    }

    // Many-to-Many Relationship with Locations
    public function locations()
    {
        return $this->belongsToMany(Location::class, 'service_location');
    }
}
