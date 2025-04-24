<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LocationType\StoreRequest;
use App\Http\Requests\LocationType\UpdateRequest;
use App\Http\Resources\LocationTypeResource;
use App\Repository\LocationTypeRepository;

class LocationTypeController extends Controller
{
    public function __construct(private LocationTypeRepository $repository) {}


    /**
     * * @OA\Get(
     *     path="/location_types",
     *     summary="Get all location types",
     *     tags={"LocationType"},
     *     description="This endpoint allows the user to retrieve all location types.",
     *     security={{ "bearerAuth":{} }},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully retrieved location types",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Warehouse"),
     *                 @OA\Property(property="description", type="string", example="A type of location for storing products")
     *             )
     *         )
     *     )
     * )
     * @OA\Response(
     *         response=401,
     *         description="Unauthorized - No valid token provided",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized - Token not provided or invalid"),
     *             @OA\Property(property="success", type="boolean", example=false)
     *         ),
     *         @OA\Examples(
     *             example="unauthorizedResponse",
     *             summary="Example response for unauthorized access",
     *             value={
     *                 "message": "Unauthorized - Token not provided or invalid",
     *                 "success": false
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Location typeS not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Location typeS not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="An unexpected error occurred.")
     *         )
     *     )
     * )
     */

    public function getLocationTypes()
    {
        try {
            $types = $this->repository->all();
            return response()->json(
                [
                    'message' => 'Location types',
                    'types' => LocationTypeResource::collection($types)
                ]
            );
        } catch (\Throwable $th) {
            return response()->json(
                ['message' => 'Location types not found'],
                404
            );
        }
    }

    /**
     * @OA\Get(
     *     path="/location_types/:id",
     *     summary="Get a specific location type",
     *     tags={"LocationType"},
     *     description="This endpoint allows the user to retrieve details of a specific location type.",
     *     security={{ "bearerAuth":{} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the location type to retrieve",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully retrieved location type",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Warehouse"),
     *             @OA\Property(property="description", type="string", example="A type of location for storing products")
     *         )
     *     ),
     * @OA\Response(
     *         response=401,
     *         description="Unauthorized - No valid token provided",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized - Token not provided or invalid"),
     *             @OA\Property(property="success", type="boolean", example=false)
     *         ),
     *         @OA\Examples(
     *             example="unauthorizedResponse",
     *             summary="Example response for unauthorized access",
     *             value={
     *                 "message": "Unauthorized - Token not provided or invalid",
     *                 "success": false
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Location type not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Location type not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="An unexpected error occurred.")
     *         )
     *     )
     * )
     */

    public function getLocationType(int $id)
    {
        $type = $this->repository->find($id);
        if (!$type) {
            return response()->json([
                'message' => 'Location type not found'
            ], 404);
        }
        return response()->json([
            'message' => 'Location type',
            'type' => new LocationTypeResource($type)
        ]);
    }

    /**
     * @OA\Post(
     *     path="/location_types",
     *     summary="Create a new location type",
     *     tags={"LocationType"},
     *     description="This endpoint allows the user to create a new location type.",
     *     security={{ "bearerAuth":{} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Warehouse"),
     *             @OA\Property(property="description", type="string", example="A type of location for storing products")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Location type created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Location type created successfully"),
     *             @OA\Property(property="location_type", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Warehouse"),
     *                 @OA\Property(property="description", type="string", example="A type of location for storing products")
     *             )
     *         )
     *     ),
     * @OA\Response(
     *         response=401,
     *         description="Unauthorized - No valid token provided",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized - Token not provided or invalid"),
     *             @OA\Property(property="success", type="boolean", example=false)
     *         ),
     *         @OA\Examples(
     *             example="unauthorizedResponse",
     *             summary="Example response for unauthorized access",
     *             value={
     *                 "message": "Unauthorized - Token not provided or invalid",
     *                 "success": false
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request - Missing or invalid data",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid data provided")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="An unexpected error occurred.")
     *         )
     *     )
     * )
     */

    public function createLocationType(StoreRequest $request)
    {
        try {
            $locationType = $this->repository->create($request->validated());

            return response()->json(
                [
                    'message' => 'Location type created',
                    'type' => new LocationTypeResource($locationType)
                ]
            );
        } catch (\Throwable $th) {
            return response()->json(
                ['message' => 'Location type not created'],
                500
            );
        }
    }

    /**
     * @OA\Put(
     *     path="/location_types/:id",
     *     summary="Update an existing location type",
     *     tags={"LocationType"},
     *     description="This endpoint allows the user to update the details of a specific location type.",
     *     security={{ "bearerAuth":{} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the location type to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Updated Warehouse"),
     *             @OA\Property(property="description", type="string", example="Updated description for the location type")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Location type updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Location type updated successfully"),
     *             @OA\Property(property="location_type", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Updated Warehouse"),
     *                 @OA\Property(property="description", type="string", example="Updated description for the location type")
     *             )
     *         )
     *     ),
     * @OA\Response(
     *         response=401,
     *         description="Unauthorized - No valid token provided",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized - Token not provided or invalid"),
     *             @OA\Property(property="success", type="boolean", example=false)
     *         ),
     *         @OA\Examples(
     *             example="unauthorizedResponse",
     *             summary="Example response for unauthorized access",
     *             value={
     *                 "message": "Unauthorized - Token not provided or invalid",
     *                 "success": false
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Location type not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Location type not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="An unexpected error occurred.")
     *         )
     *     )
     * )
     */

    public function updateLocationType(UpdateRequest $request, int $id)
    {
        try {
            $locationType = $this->repository->update($id, $request->validated());
            return response()->json(
                ['type' => new LocationTypeResource($locationType)]
            );
        } catch (\Throwable $th) {
            return response()->json(
                ['message' => 'Location type not found'],
                404
            );
        }
    }

    /**
     * @OA\Delete(
     *     path="/location_types/:id",
     *     summary="Delete a location type",
     *     tags={"LocationType"},
     *     description="This endpoint allows the user to delete a specific location type.",
     *     security={{ "bearerAuth":{} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the location type to delete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Location type deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Location type deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - No valid token provided",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized - Token not provided or invalid"),
     *             @OA\Property(property="success", type="boolean", example=false)
     *         ),
     *         @OA\Examples(
     *             example="unauthorizedResponse",
     *             summary="Example response for unauthorized access",
     *             value={
     *                 "message": "Unauthorized - Token not provided or invalid",
     *                 "success": false
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Location type not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Location type not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="An unexpected error occurred.")
     *         )
     *     )
     * )
     */

    public function deleteLocationType(int $id)
    {
        try {
            $deleted = $this->repository->delete($id);
            if (!$deleted) {
                return response()->json(
                    ['message' => 'Location type not found'],
                    404
                );
            }
            return response()->json(
                ['message' => 'Location type deleted']
            );
        } catch (\Throwable $th) {
            return response()->json(
                ['message' => 'Location type not found'],
                404
            );
        }
    }
}
