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
    public function __construct(private ClientRepository $repository)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/clients",
     *     summary="Get all clients",
     *     description="Retrieves a list of clients within a workspace.",
     *     tags={"Clients"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="workspace_id",
     *         in="query",
     *         required=false,
     *         description="Filter clients by workspace ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Clients retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Clients retrieved"),
     *             @OA\Property(property="clients", type="array", @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="first_name", type="string", example="John"),
     *                 @OA\Property(property="last_name", type="string", example="Doe"),
     *                 @OA\Property(property="status", type="string", example="active"),
     *                 @OA\Property(property="email", type="string", example="john@example.com"),
     *                 @OA\Property(property="phone", type="string", example="+880 xxxx xxxxxx"),
     *                 @OA\Property(property="in", type="string", example="12345"),
     *                 @OA\Property(property="dob", type="string", format="date", example="1990-01-01"),
     *                 @OA\Property(property="sex", type="string", example="male"),
     *                 @OA\Property(property="relationship", type="string", example="single"),
     *                 @OA\Property(property="emp_status", type="string", example="employed"),
     *                 @OA\Property(property="ethnicity", type="string", example="Asian"),
     *                 @OA\Property(property="notes", type="string", example="Some additional notes"),
     *             ))
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Something went wrong"),
     *             @OA\Property(property="errors", type="string", example="Error details")
     *         )
     *     )
     * )
     */

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

    /**
     * @OA\Get(
     *     path="/api/v1/clients/:id",
     *     summary="Get a single client",
     *     description="Retrieve details of a specific client by ID.",
     *     tags={"Clients"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the client",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Client retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Client retrieved successfully"),
     *             @OA\Property(property="client", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="first_name", type="string", example="John"),
     *                 @OA\Property(property="last_name", type="string", example="Doe"),
     *                 @OA\Property(property="status", type="string", example="active"),
     *                 @OA\Property(property="email", type="string", example="john@example.com"),
     *                 @OA\Property(property="phone", type="string", example="+880 xxxx xxxxxx"),
     *                 @OA\Property(property="in", type="string", example="12345"),
     *                 @OA\Property(property="dob", type="string", format="date", example="1990-01-01"),
     *                 @OA\Property(property="sex", type="string", example="male"),
     *                 @OA\Property(property="relationship", type="string", example="single"),
     *                 @OA\Property(property="emp_status", type="string", example="employed"),
     *                 @OA\Property(property="ethnicity", type="string", example="Asian"),
     *                 @OA\Property(property="notes", type="string", example="Some additional notes")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Client not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Client not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Something went wrong"),
     *             @OA\Property(property="errors", type="string", example="Error details")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/clients",
     *     summary="Create a new client",
     *     tags={"Clients"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"first_name", "last_name", "email", "phone"},
     *             @OA\Property(property="first_name", type="string", example="John"),
     *             @OA\Property(property="last_name", type="string", example="Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
     *             @OA\Property(property="phone", type="string", example="+1234567890"),
     *             @OA\Property(property="status", type="string", example="active"),
     *             @OA\Property(property="dob", type="string", format="date", example="1990-05-15"),
     *             @OA\Property(property="sex", type="string", example="male"),
     *             @OA\Property(property="relationship", type="string", example="single"),
     *             @OA\Property(property="emp_status", type="string", example="employed"),
     *             @OA\Property(property="ethnicity", type="string", example="Asian"),
     *             @OA\Property(property="notes", type="string", example="VIP client")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Client created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Client created successfully"),
     *             @OA\Property(property="client", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="first_name", type="string", example="John"),
     *                 @OA\Property(property="last_name", type="string", example="Doe"),
     *                 @OA\Property(property="status", type="string", example="active"),
     *                 @OA\Property(property="email", type="string", example="john@example.com"),
     *                 @OA\Property(property="phone", type="string", example="+880 xxxx xxxxxx"),
     *                 @OA\Property(property="in", type="string", example="12345"),
     *                 @OA\Property(property="dob", type="string", format="date", example="1990-01-01"),
     *                 @OA\Property(property="sex", type="string", example="male"),
     *                 @OA\Property(property="relationship", type="string", example="single"),
     *                 @OA\Property(property="emp_status", type="string", example="employed"),
     *                 @OA\Property(property="ethnicity", type="string", example="Asian"),
     *                 @OA\Property(property="notes", type="string", example="Some additional notes")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Something went wrong"),
     *             @OA\Property(property="error", type="string", example="Database error")
     *         )
     *     )
     * )
     */


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

    /**
     * @OA\Put(
     *     path="/api/clients/{id}",
     *     summary="Update an existing client",
     *     tags={"Clients"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Client ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"first_name", "last_name", "email", "phone"},
     *             @OA\Property(property="first_name", type="string", example="John"),
     *             @OA\Property(property="last_name", type="string", example="Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
     *             @OA\Property(property="phone", type="string", example="+1234567890"),
     *             @OA\Property(property="status", type="string", example="active"),
     *             @OA\Property(property="dob", type="string", format="date", example="1990-05-15"),
     *             @OA\Property(property="sex", type="string", example="male"),
     *             @OA\Property(property="relationship", type="string", example="single"),
     *             @OA\Property(property="emp_status", type="string", example="employed"),
     *             @OA\Property(property="ethnicity", type="string", example="Asian"),
     *             @OA\Property(property="notes", type="string", example="VIP client")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Client updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Client updated successfully"),
     *             @OA\Property(property="client", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="first_name", type="string", example="John"),
     *                 @OA\Property(property="last_name", type="string", example="Doe"),
     *                 @OA\Property(property="status", type="string", example="active"),
     *                 @OA\Property(property="email", type="string", example="john@example.com"),
     *                 @OA\Property(property="phone", type="string", example="+880 xxxx xxxxxx"),
     *                 @OA\Property(property="in", type="string", example="12345"),
     *                 @OA\Property(property="dob", type="string", format="date", example="1990-01-01"),
     *                 @OA\Property(property="sex", type="string", example="male"),
     *                 @OA\Property(property="relationship", type="string", example="single"),
     *                 @OA\Property(property="emp_status", type="string", example="employed"),
     *                 @OA\Property(property="ethnicity", type="string", example="Asian"),
     *                 @OA\Property(property="notes", type="string", example="Some additional notes")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Something went wrong"),
     *             @OA\Property(property="error", type="string", example="Database error")
     *         )
     *     )
     * )
     */

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

    /**
     * @OA\Delete(
     *     path="/api/v1/clients/:id",
     *     summary="Delete a client",
     *     description="Deletes a client based on the given ID.",
     *     tags={"Clients"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the client",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Client deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Client deleted successfully")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Client not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */

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
