<?php

namespace App\Repository;

use App\Models\Appointment;
use App\Repository\Implementation\BaseRepository;

class AppointmentRepository extends BaseRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct(Appointment $appointment)
    {
        parent::__construct($appointment);
    }

    public function getUserAppointment($userId)
    {
        return $this->model->where('user_id', $userId)->get();
    }
}
