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
        'payable_amount',
        'is_paid',
    ];
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
        return $this->belongsTo(Client::class);
    }
    public function services()
    {
        return $this->hasMany(InvoiceService::class);
    }
}
