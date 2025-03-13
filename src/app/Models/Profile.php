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
        'last_name',
        'note',
        'phone',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
