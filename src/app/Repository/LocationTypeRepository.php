<?php

namespace App\Repository;

use App\Models\LocationType;
use App\Repository\Implementation\BaseRepository;

class LocationTypeRepository extends BaseRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct(LocationType $locationType)
    {
        parent::__construct($locationType);
    }
}
