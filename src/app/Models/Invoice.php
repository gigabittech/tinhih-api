<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'workspace_id',
        'client_id',
        'biller_id',
        'title',
        'serial_number',
        'po_so_number',
        'tax_id',
        'issue_date',
        'due_date',
        'description',
        'subtotal',
        'payable_amount',
        'is_paid',
    ];

    protected static function booted()
    {
        static::creating(function ($invoice) {
            $lastSerial = self::max('serial_number') ?? 0;
            $invoice->serial_number = $lastSerial + 1;
        });
    }

    public function getSerialNumberAttribute($value)
    {
        return str_pad($value, 6, '0', STR_PAD_LEFT);
    }

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }
    public function biller()
    {
        return $this->belongsTo(User::class, 'biller_id');
    }
    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }
    public function client()
    {
        return $this->belongsTo(User::class);
    }
    public function services()
    {
        return $this->hasMany(InvoiceService::class);
    }

    public function scopeByWorkspace($query, $workspaceId)
    {
        return $query->where('workspace_id', $workspaceId);
    }

    public function taxes()
    {
        return $this->belongsToMany(Tax::class, 'invoice_tax', 'invoice_id', 'tax_id');
    }
}
