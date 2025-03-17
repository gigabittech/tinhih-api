<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocationType extends Model
{
    protected $fillable = [
        'logo',
        'name',
        'description',
    ];
}
