<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    public function workspaces()
    {
        return $this->hasMany(Workspace::class);
    }
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function service_team_member()
    {
        return $this->belongsToMany(Service::class, 'service_team_member');
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function currentWorkspace()
    {
        return $this->workspaces()->with(['services', 'teamMembers', 'appointments', 'locations', 'clients', 'invoices', 'taxes'])->where('active', 1)->first();
    }

    public function scopeNutralWorkspaces()
    {
        $this->workspaces()->where('active', 1)->update(['active' => 0]);
    }
}
