<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Service\StoreRequest;
use App\Http\Requests\Service\UpdateRequest;
use App\Http\Resources\Service\ServiceResource;
use App\Repository\ServiceRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ServiceController extends Controller
{
    public function __construct(private ServiceRepository $repository) {}

    /**
     * @OA\Get(
     *     path="/api/v1/services",
     *     summary="Get all Services",
     *     tags={"Services"},
     *     description="Retrieve all available services.",
     *     security={{ "bearerAuth":{} }},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully retrieved services",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="service_name", type="string", example="Consultation"),
     *                 @OA\Property(property="display_name", type="string", example="General Consultation"),
     *                 @OA\Property(property="code", type="string", example="CONS001"),
     *                 @OA\Property(property="duration", type="integer", example=30),
     *                 @OA\Property(property="price", type="number", format="float", example=50.00),
     *                 @OA\Property(property="description", type="string", example="Basic consultation service"),
     *                 @OA\Property(property="group_event", type="boolean", example=false),
     *                 @OA\Property(property="max_attendees", type="integer", example=10),
     *                 @OA\Property(property="taxable", type="boolean", example=true),
     *                 @OA\Property(property="bookable_online", type="boolean", example=true),
     *                 @OA\Property(property="allow_new_clients", type="boolean", example=true)
     *                 @OA\Property(property="team_members", type="array", example=[1,2,3])
     *                 @OA\Property(property="locations", type="array", example=[1,2,3])
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error"),
     *             @OA\Property(property="error", type="string", example="Server error message")
     *         )
     *     )
     * )
     */


    public function getServices()
    {
        try {
            $services = ServiceResource::collection($this->repository->all());
            return response()->json([
                'message' => 'Services',
                'services' => $services
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function getServicesByUser(Request $request)
    {
        try {
            $services = ServiceResource::collection($this->repository->findByUser($request->user()->id));
            return response()->json([
                'message' => 'User Services',
                'services' => $services
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/services/:id",
     *     summary="Get a specific Service",
     *     tags={"Services"},
     *     description="Retrieve details of a single service.",
     *     security={{ "bearerAuth":{} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Service ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully retrieved service",
     *         @OA\JsonContent(
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="service_name", type="string", example="Consultation"),
     *             @OA\Property(property="display_name", type="string", example="General Consultation"),
     *             @OA\Property(property="code", type="string", example="CONS001"),
     *             @OA\Property(property="duration", type="integer", example=30),
     *             @OA\Property(property="price", type="number", format="float", example=50.00),
     *             @OA\Property(property="description", type="string", example="Basic consultation service"),
     *             @OA\Property(property="group_event", type="boolean", example=false),
     *             @OA\Property(property="max_attendees", type="integer", example=10),
     *             @OA\Property(property="taxable", type="boolean", example=true),
     *             @OA\Property(property="bookable_online", type="boolean", example=true),
     *             @OA\Property(property="allow_new_clients", type="boolean", example=true)
     *             @OA\Property(property="team_members", type="array", example=[1,2,4])
     *             @OA\Property(property="locations", type="array", example=[1,2,4])
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error"),
     *             @OA\Property(property="error", type="string", example="Server error message")
     *         )
     *     )
     * )
     */

    public function getService(int $id)
    {
        try {
            $service = new ServiceResource($this->repository->find($id));
            return response()->json([
                'message' => 'Service',
                'service' => $service
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/services",
     *     summary="Create a new Service",
     *     tags={"Services"},
     *     description="Create a new service entry in the system.",
     *     security={{ "bearerAuth":{} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="service_name", type="string", example="Consultation"),
     *             @OA\Property(property="display_name", type="string", example="General Consultation"),
     *             @OA\Property(property="code", type="string", example="CONS001"),
     *             @OA\Property(property="duration", type="integer", example=30),
     *             @OA\Property(property="price", type="number", format="float", example=50.00),
     *             @OA\Property(property="description", type="string", example="Basic consultation service"),
     *             @OA\Property(property="group_event", type="boolean", example=false),
     *             @OA\Property(property="max_attendees", type="integer", example=10),
     *             @OA\Property(property="taxable", type="boolean", example=true),
     *             @OA\Property(property="bookable_online", type="boolean", example=true),
     *             @OA\Property(property="allow_new_clients", type="boolean", example=true)
     *             @OA\Property(property="team_members", type="array", example=[1,2,4])
     *             @OA\Property(property="locations", type="array", example=[1,2,4])
     *         )
     *     ),
     *     @OA\Response(response=201, description="Service created successfully"),
     *     @OA\Response(response=400, description="Invalid request data"),
     *     @OA\Response(response=401, description="Unauthorized access"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */

    public function createService(StoreRequest $request)
    {
        try {
            $service = $this->repository->create($request->validated());

            // Attach team members if provided
            if ($request->has('team_members')) {
                $service->teamMembers()->sync($request->team_members);
            }

            if ($request->locations) {
                $service->locations()->sync($request->locations);
            }
            return response()->json([
                'message' => 'Service created',
                'service' => new ServiceResource($service)
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'error' => $e->errors() // Returns all validation errors
            ], 422);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/v1/services/:id",
     *     summary="Update an existing Service",
     *     tags={"Services"},
     *     description="Update details of a specific service.",
     *     security={{ "bearerAuth":{} }},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="service_name", type="string", example="Updated Consultation"),
     *             @OA\Property(property="display_name", type="string", example="Updated General Consultation"),
     *             @OA\Property(property="code", type="string", example="CONS002"),
     *             @OA\Property(property="duration", type="integer", example=45),
     *             @OA\Property(property="price", type="number", format="float", example=60.00),
     *             @OA\Property(property="description", type="string", example="Updated description"),
     *             @OA\Property(property="group_event", type="boolean", example=false),
     *             @OA\Property(property="max_attendees", type="integer", example=15),
     *             @OA\Property(property="taxable", type="boolean", example=true),
     *             @OA\Property(property="bookable_online", type="boolean", example=true),
     *             @OA\Property(property="allow_new_clients", type="boolean", example=false)
     *             @OA\Property(property="team_members", type="array", example=[1,2,4])
     *             @OA\Property(property="locations", type="array", example=[1,2,4])
     *         )
     *     ),
     *     @OA\Response(response=200, description="Service updated successfully"),
     *     @OA\Response(response=400, description="Invalid request data"),
     *     @OA\Response(response=404, description="Service not found"),
     *     @OA\Response(response=401, description="Unauthorized access"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */

    public function updateService(UpdateRequest $request, int $id)
    {
        try {
            $service = $this->repository->update($id, $request->validated());
            // Update team members if provided
            if ($request->has('team_members')) {
                $service->teamMembers()->sync($request->team_members);
            }

            // Update locations
            if ($request->has('locations')) {
                $service->locations()->sync($request->locations);
            }
            return response()->json([
                'message' => 'Service updated',
                'service' => new ServiceResource($service)
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/services/:id",
     *     summary="Delete a Service",
     *     tags={"Services"},
     *     description="Remove a service from the system.",
     *     security={{ "bearerAuth":{} }},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Service deleted successfully"),
     *     @OA\Response(response=404, description="Service not found"),
     *     @OA\Response(response=401, description="Unauthorized access"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */

    public function deleteService(int $id)
    {
        try {
            $this->repository->delete($id);
            return response()->json([
                'message' => 'Service deleted'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
