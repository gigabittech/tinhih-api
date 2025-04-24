<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TeamMember\StoreRequest;
use App\Http\Requests\TeamMember\UpdateRequest;
use App\Http\Resources\TeamMemberResource;
use App\Repository\TeamMemberRepository;
use Illuminate\Http\Request;

class TeamMemberController extends Controller
{
    public function __construct(private TeamMemberRepository $repository) {}

    /**
     * @OA\Get(
     *     path="/members",
     *     summary="Get all members",
     *     description="Retrieve a list of members.",
     *     tags={"Members"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of members retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="members retrieved successfully"),
     *             @OA\Property(
     *                 property="members",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="first_name", type="string", example="Jhon"),
     *                     @OA\Property(property="last_name", type="string", example="Doe"),
     *                     @OA\Property(property="email", type="string", example="jhon@example.com"),
     *                     @OA\Property(property="phone_number", type="string", example="+880 xxxx xxxxxx"),
     *                     @OA\Property(property="job_title", type="string", example="Mental Health Specialist"),
     *                     @OA\Property(property="avatar", type="string", example=""),
     *                     @OA\Property(property="npi", type="string", example=""),
     *                     @OA\Property(property="texonomy", type="string", example=""),
     *                     @OA\Property(property="workspace_id", type="string", example="workspace_id"),
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

    public function getMembers(Request $request)
    {
        try {
            $members = $this->repository->getUserTeamMembers($request->user()->id, $request->workspace_id);
            return response()->json(['message' => "Team members retrieve successfull", 'members' => TeamMemberResource::collection($members)], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/members/:id",
     *     summary="Get a member",
     *     description="Retrieve a  member.",
     *     tags={"Members"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Member retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="member retrieved successfully"),
     *             @OA\Property(
     *                 property="members",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="first_name", type="string", example="Jhon"),
     *                     @OA\Property(property="last_name", type="string", example="Doe"),
     *                     @OA\Property(property="email", type="string", example="jhon@example.com"),
     *                     @OA\Property(property="phone_number", type="string", example="+880 xxxx xxxxxx"),
     *                     @OA\Property(property="job_title", type="string", example="Mental Health Specialist"),
     *                     @OA\Property(property="avatar", type="string", example=""),
     *                     @OA\Property(property="npi", type="string", example=""),
     *                     @OA\Property(property="texonomy", type="string", example=""),
     *                     @OA\Property(property="workspace_id", type="string", example="workspace_id"),
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


    public function getMember(int $id)
    {
        try {
            $member = $this->repository->find($id);
            return response()->json(['message' => "Team member retrieve successfull", 'member' => new TeamMemberResource($member)], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/members",
     *     summary="Create a new member",
     *     description="Creates a new team member and associates services.",
     *     tags={"Members"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"workspace_id", "first_name", "last_name", "email"},
     *             @OA\Property(property="workspace_id", type="string", example="1"),
     *             @OA\Property(property="first_name", type="string", example="John"),
     *             @OA\Property(property="last_name", type="string", example="Doe"),
     *             @OA\Property(property="email", type="string", example="john@example.com"),
     *             @OA\Property(property="phone_number", type="string", example="+880 xxxx xxxxxx"),
     *             @OA\Property(property="job_title", type="string", example="Mental Health Specialist"),
     *             @OA\Property(property="npi", type="string", example=""),
     *             @OA\Property(property="avatar", type="string", example=""),
     *             @OA\Property(property="taxonomy", type="string", example=""),
     *             @OA\Property(property="services", type="array", @OA\Items(type="string"), example={}),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Member created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Team member created successfully"),
     *             @OA\Property(
     *                 property="member",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="first_name", type="string", example="John"),
     *                 @OA\Property(property="last_name", type="string", example="Doe"),
     *                 @OA\Property(property="email", type="string", example="john@example.com"),
     *                 @OA\Property(property="phone_number", type="string", example="+880 xxxx xxxxxx"),
     *                 @OA\Property(property="job_title", type="string", example="Mental Health Specialist"),
     *                 @OA\Property(property="avatar", type="string", example=""),
     *                 @OA\Property(property="npi", type="string", example=""),
     *                 @OA\Property(property="taxonomy", type="string", example=""),
     *                 @OA\Property(property="workspace_id", type="string", example="1"),
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


    public function createMember(StoreRequest $request)
    {
        try {
            $member = $this->repository->create($request->validated());
            $member->services()->sync($request->services);
            return response()->json(['message' => "Team member retrieve successfull", 'member' => new TeamMemberResource($member)], 201);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/members/:id",
     *     summary="Update a member",
     *     description="Updates an existing team member.",
     *     tags={"Members"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Member ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"workspace_id", "first_name", "last_name", "email"},
     *             @OA\Property(property="workspace_id", type="string", example="1"),
     *             @OA\Property(property="first_name", type="string", example="John"),
     *             @OA\Property(property="last_name", type="string", example="Doe"),
     *             @OA\Property(property="email", type="string", example="john@example.com"),
     *             @OA\Property(property="phone_number", type="string", example="+880 xxxx xxxxxx"),
     *             @OA\Property(property="job_title", type="string", example="Mental Health Specialist"),
     *             @OA\Property(property="npi", type="string", example=""),
     *             @OA\Property(property="avatar", type="string", example=""),
     *             @OA\Property(property="taxonomy", type="string", example=""),
     *             @OA\Property(property="services", type="array", @OA\Items(type="string"), example={}),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Member updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Team member updated successfully"),
     *             @OA\Property(property="member", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="first_name", type="string", example="John"),
     *                 @OA\Property(property="last_name", type="string", example="Doe"),
     *                 @OA\Property(property="email", type="string", example="john@example.com"),
     *                 @OA\Property(property="phone_number", type="string", example="+880 xxxx xxxxxx"),
     *                 @OA\Property(property="job_title", type="string", example="Mental Health Specialist"),
     *                 @OA\Property(property="avatar", type="string", example=""),
     *                 @OA\Property(property="npi", type="string", example=""),
     *                 @OA\Property(property="taxonomy", type="string", example=""),
     *                 @OA\Property(property="workspace_id", type="string", example="1"),
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

    public function updateMember(int $id, UpdateRequest $request)
    {
        try {
            $member = $this->repository->update($id, $request->validated());
            $member->services()->sync($request->services);
            return response()->json(['message' => "Team member update successfull", 'member' => new TeamMemberResource($member)], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/members/:id",
     *     summary="Delete a member",
     *     description="Deletes a specific team member.",
     *     tags={"Members"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Member ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Member deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Team member deleted successfully")
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

    public function deleteMember(int $id)
    {
        try {
            $member = $this->repository->delete($id);
            return response()->json(['message' => "Team member retrieve successfull", 'member' => $member], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
