<?php

namespace App\Repository;

use App\Models\Location;
use App\Repository\Implementation\BaseRepository;

class LocationRepository extends BaseRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct(Location $location)
    {
        parent::__construct($location);
    }
}
