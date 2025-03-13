<?php

namespace App\Repository;

use App\Models\User;
use App\Repository\Implementation\BaseRepository;

class UserRepository extends BaseRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct(User $user)
    {
        parent::__construct($user);
    }


    public function findByEmail(string $email)
    {
        return $this->model->where('email', $email)->first();
    }
}
