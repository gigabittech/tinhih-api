<?php

namespace App\Repository;

use App\Models\Service;
use App\Repository\Implementation\BaseRepository;

class ServiceRepository extends BaseRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct(Service $service)
    {
        parent::__construct($service);
    }

    public function findByUser(int $userId)
    {
        return $this->model->where('user_id', $userId)->get();
    }

    public function getWorkspaceServices($workspaceId)
    {
        return $this->model->where('workspace_id', $workspaceId)->get();
    }
}
