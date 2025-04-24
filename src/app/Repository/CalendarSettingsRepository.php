<?php

namespace App\Repository;

use App\Models\CalendarSetting;
use App\Repository\Implementation\BaseRepository;

class CalendarSettingsRepository extends BaseRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct(CalendarSetting $model)
    {
        parent::__construct($model);
    }

}
