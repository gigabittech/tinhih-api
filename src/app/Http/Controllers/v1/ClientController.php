<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\StoreRequest;
use App\Http\Requests\Client\UpdateRequest;
use App\Http\Resources\Client\ClientResource;
use App\Repository\ClientRepository;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function __construct(private ClientRepository $repository) {}

    public function getClients(Request $request)
    {
        try {
            //code...
            $clients = $this->repository->getWorkspaceClients($request);
            return response()->json([
                'message' => 'Clients retrieved',
                'clients' => ClientResource::collection($clients)
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => "Something wents wrong",
                "errors" => $th->getMessage()
            ], 500);
        }
    }
    public function getClient(int $id)
    {
        try {
            //code...
            $client = $this->repository->find($id);
            if ($client) {
                return response()->json([
                    'message' => 'Client retrieved',
                    'clients' => new ClientResource($client)
                ], 200);
            }
            return response()->json([
                'message' => 'Not found'
            ], 400);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => "Something wents wrong"
            ], 500);
        }
    }

    public function createClient(StoreRequest $request)
    {
        try {
            //code...
            $clients = $this->repository->create($request->validated());
            return response()->json([
                'message' => 'Client retrieved',
                'clients' => new ClientResource($clients)
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => "Something wents wrong"
            ], 500);
        }
    }

    public function updateClient(UpdateRequest $request, $id)
    {
        try {
            //code...
            $client = $this->repository->update($id, $request->validated());
            return response()->json([
                'message' => 'Client updated',
                'client' => new ClientResource($client)
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => "Something wents wrong"
            ], 500);
        }
    }
    public function deleteClient($id)
    {
        try {
            //code...
            $client = $this->repository->delete($id);
            return response()->json([
                'message' => 'Client deleted',
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => "Something wents wrong"
            ], 500);
        }
    }
}
