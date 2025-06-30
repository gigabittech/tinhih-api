<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Http\Request;

class BookingApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/booking/{workspaceId}/{userId}",
     *     operationId="getUserInWorkspace",
     *     tags={"Booking Api"},
     *     summary="Get a user in a specific workspace along with services and locations",
     *     description="Returns user details only if the user is part of the workspace, including related services and locations.",
     *     
     *     @OA\Parameter(
     *         name="workspaceId",
     *         in="path",
     *         required=true,
     *         description="ID of the workspace",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         required=true,
     *         description="ID of the user",
     *         @OA\Schema(type="integer")
     *     ),
     *     
     *     @OA\Response(
     *         response=200,
     *         description="User found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(
     *                 property="workspaces",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=10),
     *                     @OA\Property(property="name", type="string", example="Design Team"),
     *                     @OA\Property(
     *                         property="services",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=101),
     *                             @OA\Property(property="name", type="string", example="Web Development")
     *                         )
     *                     ),
     *                     @OA\Property(
     *                         property="locations",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=201),
     *                             @OA\Property(property="address", type="string", example="Dhaka, Bangladesh")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     
     *     @OA\Response(
     *         response=404,
     *         description="Workspace or user not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Workspace not found")
     *         )
     *     )
     * )
     */

    public function show($workspaceId, $userId)
    {
        $workspace = Workspace::with(['services', 'locations'])->find($workspaceId);

        if (!$workspace) {
            return response()->json([
                'message' => 'Workspace not found'
            ], 404);
        }

        $user = User::whereHas('workspaces', function ($query) use ($workspaceId) {
            $query->where('id', $workspaceId);
        })->with([
                    'workspaces' => function ($query) use ($workspaceId) {
                        $query->where('id', $workspaceId)->with(['services', 'locations']);
                    }
                ])->find($userId);

        if (!$user) {
            return response()->json([
                'message' => 'User not found in this workspace'
            ], 404);
        }
        return response()->json($user);
    }
}
