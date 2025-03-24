<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Client extends Model
{
    protected $fillable = [
        'workspace_id',
        'first_name',
        'last_name',
        'email',
        'status',
        'phone',
        'in',
        'dob',
        'sex',
        'relationship',
        'emp_status',
        'ethnicity',
        'notes',
    ];


    public function appointments() {}
    public function invoices() {}
    public function documents() {}

    public function workspace()
    {
        $this->belongsTo(Workspace::class);
    }
}
