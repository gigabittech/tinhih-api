<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workspace extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'countryCode',
        'website',
        'billingAddress',
        'businessName',
        'teamSize',
        'timeZone',
        'active',
        'profession',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function teamMembers()
    {
        return $this->hasMany(TeamMember::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    // public function contacts()
    // {
    //     return $this->hasMany(Contact::class);
    // }
    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function taxes()
    {
        return $this->hasMany(Tax::class);
    }

    public function calendarSettings(){
        return $this->hasOne(CalendarSetting::class);
    }
}
