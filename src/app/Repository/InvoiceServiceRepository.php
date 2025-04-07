<?php

namespace App\Repository;

use App\Models\InvoiceService;
use App\Repository\Implementation\BaseRepository;

class InvoiceServiceRepository extends BaseRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct(InvoiceService $invoiceService)
    {
        parent::__construct($invoiceService);
    }
}
