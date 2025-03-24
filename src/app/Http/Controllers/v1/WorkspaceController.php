<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Workspace\StoreRequest;
use App\Http\Requests\Workspace\UpdateRequest;
use App\Http\Resources\WorkspaceResource;
use App\Repository\WorkspaceRepository;
use Illuminate\Http\Request;

class WorkspaceController extends Controller
{
    public function __construct(private WorkspaceRepository $repository) {}


    public function getWorkspaces()
    {
        try {
            return response()->json(['message' => "Workspaces retrieve successfull", 'workspaces' => WorkspaceResource::collection($this->repository->all())], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
            ], 500);
        }
    }
    public function getUserWorkspaces(Request $request)
    {
        try {
            $workspaces = $this->repository->getUserWorkspaces($request->user()->id);
            return response()->json([
                'message' => "User workspaces retrieve successfull",
                'workspaces' => WorkspaceResource::collection($workspaces)
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function getWorkspace(int $id)
    {
        try {
            $workspace = $this->repository->find($id);
            return response()->json(['message' => "Workspace retrieve successfull", 'workspace' =>  new WorkspaceResource($workspace)], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function createWorkspace(StoreRequest $request)
    {
        try {
            $workspace = $this->repository->create($request->all());
            return response()->json(['message' => "Workspace create successfull", 'workspace' => new WorkspaceResource($workspace)], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function updateWorkspace(int $id, UpdateRequest $request)
    {
        try {
            $workspace = $this->repository->update($id, $request->validated());
            return response()->json(['message' => "Workspace update successfull", 'workspace' =>  new WorkspaceResource($workspace)], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function deleteWorkspace(int $id)
    {
        try {
            $this->repository->delete($id);
            return response()->json(['message' => "Workspace delete successfull"], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function toggleWorkspace(Request $request)
    {
        try {
            $workspace = $this->repository->toggle($request->user(), $request->id);
            return response()->json(['message' => "Workspace toggle successfull", 'workspace' =>  new WorkspaceResource($workspace)], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
