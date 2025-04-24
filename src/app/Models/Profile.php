<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'address',
        'avatar',
        'dob',
        'first_name',
        'full_name',
        'last_name',
        'middle_name',
        'preferred_name',
        'phone',
        'gender',
        'note',
        'locale',
        'time_zone'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
