<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $fillable = [
        'workspace_id',
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'job_title',
        'npi',
        'avatar',
        'texonomy',
    ];


    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'service_team_member');
    }
}
