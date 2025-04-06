<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $fillable = [
        'name',
        'percentage',
        'is_default',
        'workspace_id',
    ];

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
    public function scopeNotDefault($query)
    {
        return $query->where('is_default', false);
    }
    public function scopeByWorkspace($query, $workspaceId)
    {
        return $query->where('workspace_id', $workspaceId);
    }
    public function scopeByName($query, $name)
    {
        return $query->where('name', 'like', '%' . $name . '%');
    }
    public function scopeByPercentage($query, $percentage)
    {
        return $query->where('percentage', 'like', '%' . $percentage . '%');
    }
}
