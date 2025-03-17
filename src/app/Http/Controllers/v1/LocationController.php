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


    public function getLocations()
    {
        return response()->json([
            'message' => 'Locations',
            'locations' => LocationResource::collection($this->repository->all())
        ]);
    }

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

    public function createLocation(StoreRequest $request)
    {
        $location = $this->repository->create($request->validated());
        return response()->json([
            'message' => 'Location created',
            'location' => new LocationResource($location)
        ], 201);
    }

    public function updateLocation(UpdateRequest $request, $id)
    {
        $location = $this->repository->find($id);
        if (!$location) {
            return response()->json([
                'message' => 'Location not found'
            ], 404);
        }
        $location = $this->repository->update($request->validated(), $id);
        return response()->json([
            'message' => 'Location updated',
            'location' => new LocationResource($location)
        ]);
    }

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
