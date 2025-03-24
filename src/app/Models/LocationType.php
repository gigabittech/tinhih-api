<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocationType extends Model
{
    protected $fillable = [
        'type',
        'logo',
        'name',
        'description',
    ];


    public function locations()
    {
        return $this->hasMany(Location::class);
    }
}
