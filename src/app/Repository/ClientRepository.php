<?php

namespace App\Repository;

use App\Models\Client;
use App\Repository\Implementation\BaseRepository;
use Illuminate\Http\Request;

class ClientRepository extends BaseRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct(Client $client)
    {
        parent::__construct($client);
    }

    public function getWorkspaceClients(Request $request)
    {
        return $request->user()->workspaces()->where('active', 1)->first()->clients;
    }
}
