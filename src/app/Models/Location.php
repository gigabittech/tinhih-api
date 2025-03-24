<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'workspace_id',
        'type_id',
        'phone',
        'address',
        'link',
        'display_name',
        'city',
        'state',
        'zip_code',
        'country',
    ];

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'location_service');
    }

    public function appointment()
    {
        return $this->hasMany(Appointment::class);
    }

    public function locationType()
    {
        return $this->belongsTo(LocationType::class, 'type_id');
    }
}
