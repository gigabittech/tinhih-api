<?php

namespace App\Repository;

use App\Models\Invoice;
use App\Repository\Implementation\BaseRepository;

class InvoiceRepository extends BaseRepository

{
    /**
     * Create a new class instance.
     */
    public function __construct(Invoice $model)
    {
        parent::__construct($model);
    }
}
