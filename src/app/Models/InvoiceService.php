<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceService extends Model
{
    protected $fillable = [
        'invoice_id',
        'date',
        'service_id',
        'code',
        'unit',
        'amount',
        'price',
        'tax',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
