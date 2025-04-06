<?php

namespace App\Repository;

use App\Models\Tax;
use App\Repository\Implementation\BaseRepository;

class TaxRepository extends BaseRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct(Tax $tax)
    {
        parent::__construct($tax);
    }
}
