<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LocationType\StoreRequest;
use App\Http\Requests\LocationType\UpdateRequest;
use App\Http\Resources\LocationTypeResource;
use App\Repository\LocationTypeRepository;
use Illuminate\Http\Request;

class LocationTypeController extends Controller
{
    public function __construct(private LocationTypeRepository $repository) {}


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

    public function deleteLocationType(int $id)
    {
        try {
            $deleted = $this->repository->delete($id);
            if (!$deleted) {
                return response()->json(
                    ['message' => 'Location type not found', 'id' => $id],
                    404
                );
            }
            return response()->json(
                ['message' => 'Location type deleted', 'id' => $id]
            );
        } catch (\Throwable $th) {
            return response()->json(
                ['message' => 'Location type not found'],
                404
            );
        }
    }
}
