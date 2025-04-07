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

    public function getWorkspaceTaxes($workspaceId)
    {
        return $this->model->byWorkspace($workspaceId)->get();
    }

    public function getWorkspaceTax($workspaceId, $taxId)
    {
        return $this->model->byWorkspace($workspaceId)->findOrFail($taxId);
    }
}
