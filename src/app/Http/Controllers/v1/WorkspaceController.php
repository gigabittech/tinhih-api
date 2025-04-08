<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Workspace\SetupRequest;
use App\Http\Requests\Workspace\StoreRequest;
use App\Http\Requests\Workspace\UpdateRequest;
use App\Http\Resources\WorkspaceResource;
use App\Repository\WorkspaceRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkspaceController extends Controller
{
    public function __construct(private WorkspaceRepository $repository) {}

    /**
     * @OA\Get(
     *     path="v1/workspaces",
     *     summary="Get all workspaces",
     *     description="Retrieve a list of workspaces.",
     *     tags={"Workspaces"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of workspaces retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Workspaces retrieved successfully"),
     *             @OA\Property(
     *                 property="workspaces",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Tech Hub"),
     *                     @OA\Property(property="active", type="boolean", example=true),
     *                     @OA\Property(property="country", type="string", example="Bangladesh"),
     *                     @OA\Property(property="profession", type="string", example="Software Engineer"),
     *                     @OA\Property(property="url", type="string", example="https://workspace.example.com"),
     *                     @OA\Property(
     *                         property="locations",
     *                         type="array",
     *                         @OA\Items(type="string", example="Dhaka, Bangladesh")
     *                     ),
     *                     @OA\Property(
     *                         property="members",
     *                         type="array",
     *                         @OA\Items(type="string", example="John Doe")
     *                     ),
     *                     @OA\Property(
     *                         property="services",
     *                         type="array",
     *                         @OA\Items(type="string", example="Web Development")
     *                     ),
     *                     @OA\Property(
     *                         property="appointments",
     *                         type="array",
     *                         @OA\Items(type="string", example="2024-03-25 10:00 AM")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Internal server error")
     *         )
     *     )
     * )
     */

    public function getWorkspaces()
    {
        try {
            return response()->json(
                [
                    'message' => "Workspaces retrieve successfull",
                    'workspaces' => WorkspaceResource::collection($this->repository->all())
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="v1/workspaces/user",
     *     summary="Get all workspaces",
     *     description="Retrieve a list of workspaces.",
     *     tags={"Workspaces"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of user workspaces retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Workspaces retrieved successfully"),
     *             @OA\Property(
     *                 property="workspaces",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Tech Hub"),
     *                     @OA\Property(property="active", type="boolean", example=true),
     *                     @OA\Property(property="country", type="string", example="Bangladesh"),
     *                     @OA\Property(property="profession", type="string", example="Software Engineer"),
     *                     @OA\Property(property="url", type="string", example="https://workspace.example.com"),
     *                     @OA\Property(
     *                         property="locations",
     *                         type="array",
     *                         @OA\Items(type="string", example="Dhaka, Bangladesh")
     *                     ),
     *                     @OA\Property(
     *                         property="members",
     *                         type="array",
     *                         @OA\Items(type="string", example="John Doe")
     *                     ),
     *                     @OA\Property(
     *                         property="services",
     *                         type="array",
     *                         @OA\Items(type="string", example="Web Development")
     *                     ),
     *                     @OA\Property(
     *                         property="appointments",
     *                         type="array",
     *                         @OA\Items(type="string", example="2024-03-25 10:00 AM")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Internal server error")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="v1/workspaces/:id",
     *     summary="Get a workspace by ID",
     *     description="Retrieve details of a single workspace by its ID.",
     *     tags={"Workspaces"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Workspace ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Workspace retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Tech Hub"),
     *             @OA\Property(property="active", type="boolean", example=true),
     *             @OA\Property(property="url", type="string", example="https://workspace.example.com"),
     *             @OA\Property(
     *                 property="members",
     *                 type="array",
     *                 @OA\Items(type="string", example="John Doe")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Workspace not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Workspace not found")
     *         )
     *     )
     * )
     */


    public function getWorkspace(int $id)
    {
        try {
            $workspace = $this->repository->find($id);
            if (!$workspace) {
                return response()->json(['message' => "Workspace not found"], 404);
            }
            return response()->json(['message' => "Workspace retrieve successfull", 'workspace' =>  new WorkspaceResource($workspace)], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="v1/workspaces",
     *     summary="Create a workspace",
     *     tags={"Workspaces"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "country"},
     *             @OA\Property(property="name", type="string", example="My Workspace"),
     *             @OA\Property(property="country", type="string", example="Bangladesh"),
     *             @OA\Property(property="profession", type="string", example="Software Engineer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Workspace created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Workspace create successful"),
     *             @OA\Property(property="workspace", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="My Workspace"),
     *                 @OA\Property(property="country", type="string", example="Bangladesh"),
     *                 @OA\Property(property="profession", type="string", example="Software Engineer")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    public function createWorkspace(StoreRequest $request)
    {
        try {
            $workspace = $this->repository->create($request->all());
            return response()->json([
                'message' => "Workspace create successful",
                'workspace' => new WorkspaceResource($workspace)
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
            ], 500);
        }
    }


    /**
     * Update a workspace
     *
     * @OA\Put(
     *     path="v1/workspaces/:id",
     *     summary="Update an existing workspace",
     *     tags={"Workspaces"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "status"},
     *             @OA\Property(property="name", type="string", example="Updated Workspace Name"),
     *             @OA\Property(property="status", type="string", example="active")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Workspace updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Workspace update successful"),
     *             @OA\Property(property="workspace", type="object",
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="name", type="string", example="Updated Workspace Name"),
     *                  @OA\Property(property="status", type="string", example="active")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    public function updateWorkspace(int $id, UpdateRequest $request)
    {
        try {
            $workspace = $this->repository->update($id, $request->validated());
            return response()->json([
                'message' => "Workspace update successful",
                'workspace' => [
                    'id' => $workspace->id,
                    'name' => $workspace->name,
                    'status' => $workspace->status, // Adjust based on your actual model properties
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
            ], 500);
        }
    }


    /**
     * @OA\Delete(
     *     path="v1/workspaces/:id",
     *     summary="Delete a workspace",
     *     description="Remove a workspace by ID.",
     *     tags={"Workspaces"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Workspace ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Workspace deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Workspace deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Workspace not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Workspace not found")
     *         )
     *     )
     * )
     */

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

    /**
     * Toggle a workspace (Activate/Deactivate)
     *
     * @OA\Post(
     *     path="v1/workspaces/toggle",
     *     summary="Toggle workspace status",
     *     tags={"Workspaces"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id"},
     *             @OA\Property(property="id", type="integer", example=1, description="ID of the workspace to toggle")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Workspace toggle successful",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Workspace toggle successful"),
     *             @OA\Property(property="workspace", type="object",
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="name", type="string", example="Workspace Name"),
     *                  @OA\Property(property="status", type="string", example="active"),
     *             )
     *         )
     *     ),
     *     @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    public function toggleWorkspace(Request $request)
    {
        try {
            $workspace = $this->repository->toggle($request->user(), $request->id);
            return response()->json([
                'message' => "Workspace toggle successful",
                'workspace' => new WorkspaceResource($workspace)
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/setup",
     *     summary="Setup workspace for the user",
     *     description="This endpoint sets up a new workspace for the user along with their profile information.",
     *     tags={"Auth Setup"},
     *     security={{ "bearerAuth":{} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"first_name", "last_name", "profession", "countryCode", "teamSize", "businessName", "full_name", "preferred_name", "active"},
     *             @OA\Property(property="first_name", type="string", example="John"),
     *             @OA\Property(property="last_name", type="string", example="Doe"),
     *             @OA\Property(property="profession", type="string", example="Developer"),
     *             @OA\Property(property="countryCode", type="string", example="US"),
     *             @OA\Property(property="teamSize", type="string", example="justMe", enum={"justMe", "inTen", "moreThanTen"}),
     *             @OA\Property(property="timeZone", type="string", example="GMT+1"),
     *             @OA\Property(property="businessName", type="string", example="Tech Innovations"),
     *             @OA\Property(property="full_name", type="string", example="John Doe"),
     *             @OA\Property(property="preferred_name", type="string", example="John"),
     *             @OA\Property(property="active", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Workspace setup successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Workspace setup successful"),
     *             @OA\Property(property="workspace", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="active", type="boolean", example=true),
     *                 @OA\Property(property="businessName", type="string", example="Tech Innovations"),
     *                 @OA\Property(property="countryCode", type="string", example="US"),
     *                 @OA\Property(property="website", type="string", example=""),
     *                @OA\Property(property="locations", type="array", @OA\Items(type="object")),
     * @OA\Property(property="members", type="array", @OA\Items(type="object")),
     * @OA\Property(property="services", type="array", @OA\Items(type="object")),
     * @OA\Property(property="appointments", type="array", @OA\Items(type="object")),
     * @OA\Property(property="invoices", type="array", @OA\Items(type="object")),
     * @OA\Property(property="taxes", type="array", @OA\Items(type="object")),
     * @OA\Property(property="clients", type="array", @OA\Items(type="object")),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error setting up workspace",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error message from the exception")
     *         )
     *     )
     * )
     */
    public function setupWorkspace(SetupRequest $request)
    {
        try {
            DB::beginTransaction();
            $profile = $request->user()->profile()->create($request->validated());
            $workspace = $this->repository->setup($request->user(), $request->validated());
            DB::commit();
            return response()->json([
                'message' => "Workspace setup successful",
                'workspace' => new WorkspaceResource($workspace)
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
