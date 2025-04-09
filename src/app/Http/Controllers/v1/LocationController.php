<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Location\StoreRequest;
use App\Http\Requests\Location\UpdateRequest;
use App\Http\Resources\Location\LocationResource;
use App\Repository\LocationRepository;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function __construct(private LocationRepository $repository) {}

    /**
     * @OA\Get(
     *     path="/locations",
     *     summary="Get all Locations",
     *     tags={"Locations"},
     *     description="Retrieve all available locations with pagination.",
     *     security={{ "bearerAuth":{} }},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of results per page",
     *         required=false,
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully retrieved paginated locations",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="current_page", type="integer", example=1),
     *             @OA\Property(property="per_page", type="integer", example=10),
     *             @OA\Property(property="total", type="integer", example=50),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="type_id", type="integer", example=5),
     *                     @OA\Property(property="user_id", type="integer", example=1001),
     *                     @OA\Property(property="phone", type="string", example="+1234567890"),
     *                     @OA\Property(property="address", type="string", example="123 Main St"),
     *                     @OA\Property(property="link", type="string", example="https://provider.com/location"),
     *                     @OA\Property(property="display_name", type="string", example="Central Clinic"),
     *                     @OA\Property(property="city", type="string", example="New York"),
     *                     @OA\Property(property="state", type="string", example="NY"),
     *                     @OA\Property(property="zip_code", type="string", example="10001"),
     *                     @OA\Property(property="country", type="string", example="USA")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - No valid token provided",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized - Token not provided or invalid"),
     *             @OA\Property(property="success", type="boolean", example=false)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Locations not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No locations found")
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

    public function getLocations()
    {
        return response()->json([
            'message' => 'Locations',
            'locations' => LocationResource::collection($this->repository->all())
        ]);
    }

    public function getUserLocations(Request $request)
    {
        dd($request->user()->id);
        return response()->json([
            'message' => 'User Locations',
            'locations' => LocationResource::collection($this->repository->getUserLocations($request->user()->id))
        ]);
    }



    /**
     * @OA\Get(
     *     path="/locations/:id",
     *     summary="Get a specific Location",
     *     tags={"Locations"},
     *     description="Retrieve details of a specific location by ID.",
     *     security={{ "bearerAuth":{} }},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully retrieved location details",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="type_id", type="integer", example=5),
     *                     @OA\Property(property="user_id", type="integer", example=1001),
     *                     @OA\Property(property="phone", type="string", example="+1234567890"),
     *                     @OA\Property(property="address", type="string", example="123 Main St"),
     *                     @OA\Property(property="link", type="string", example="https://provider.com/location"),
     *                     @OA\Property(property="display_name", type="string", example="Central Clinic"),
     *                     @OA\Property(property="city", type="string", example="New York"),
     *                     @OA\Property(property="state", type="string", example="NY"),
     *                     @OA\Property(property="zip_code", type="string", example="10001"),
     *                     @OA\Property(property="country", type="string", example="USA")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Location not found"),
     *     @OA\Response(response=401, description="Unauthorized access")
     * )
     */

    public function getLocation($id)
    {
        $location = $this->repository->find($id);
        if (!$location) {
            return response()->json([
                'message' => 'Location not found'
            ], 404);
        }
        return response()->json([
            'message' => 'Location',
            'location' => new LocationResource($location)
        ]);
    }


    /**
     * @OA\Post(
     *     path="/locations",
     *     summary="Create a new Location",
     *     tags={"Locations"},
     *     description="Create a new location entry.",
     *     security={{ "bearerAuth":{} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"display_name"},
     *             @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="type_id", type="integer", example=5),
     *                     @OA\Property(property="user_id", type="integer", example=1001),
     *                     @OA\Property(property="phone", type="string", example="+1234567890"),
     *                     @OA\Property(property="address", type="string", example="123 Main St"),
     *                     @OA\Property(property="link", type="string", example="https://provider.com/location"),
     *                     @OA\Property(property="display_name", type="string", example="Central Clinic"),
     *                     @OA\Property(property="city", type="string", example="New York"),
     *                     @OA\Property(property="state", type="string", example="NY"),
     *                     @OA\Property(property="zip_code", type="string", example="10001"),
     *                     @OA\Property(property="country", type="string", example="USA")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Location created successfully"),
     *     @OA\Response(response=400, description="Invalid request data"),
     *     @OA\Response(response=401, description="Unauthorized access")
     * )
     */
    public function createLocation(StoreRequest $request)
    {
        $location = $this->repository->create($request->validated());
        return response()->json([
            'message' => 'Location created',
            'location' => new LocationResource($location)
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/locations/:id",
     *     summary="Update an existing Location",
     *     tags={"Locations"},
     *     description="Update details of a specific location.",
     *     security={{ "bearerAuth":{} }},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="type_id", type="integer", example=5),
     *                     @OA\Property(property="user_id", type="integer", example=1001),
     *                     @OA\Property(property="phone", type="string", example="+1234567890"),
     *                     @OA\Property(property="address", type="string", example="123 Main St"),
     *                     @OA\Property(property="link", type="string", example="https://provider.com/location"),
     *                     @OA\Property(property="display_name", type="string", example="Central Clinic"),
     *                     @OA\Property(property="city", type="string", example="New York"),
     *                     @OA\Property(property="state", type="string", example="NY"),
     *                     @OA\Property(property="zip_code", type="string", example="10001"),
     *                     @OA\Property(property="country", type="string", example="USA")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Location updated successfully"),
     *     @OA\Response(response=400, description="Invalid request data"),
     *     @OA\Response(response=404, description="Location not found"),
     *     @OA\Response(response=401, description="Unauthorized access")
     * )
     */
    public function updateLocation(UpdateRequest $request, $id)
    {
        $location = $this->repository->find($id);
        if (!$location) {
            return response()->json([
                'message' => 'Location not found',
            ], 404);
        }
        $location = $this->repository->update($request->validated(), $id);
        return response()->json([
            'message' => 'Location updated',
            'location' => new LocationResource($location)
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/locations/:id",
     *     summary="Delete a Location",
     *     tags={"Locations"},
     *     description="Remove a location from the system.",
     *     security={{ "bearerAuth":{} }},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Location deleted successfully"),
     *     @OA\Response(response=404, description="Location not found"),
     *     @OA\Response(response=401, description="Unauthorized access")
     * )
     */

    public function deleteLocation($id)
    {
        $location = $this->repository->find($id);
        if (!$location) {
            return response()->json([
                'message' => 'Location not found'
            ], 404);
        }
        $this->repository->delete($id);
        return response()->json([
            'message' => 'Location deleted'
        ]);
    }
}
