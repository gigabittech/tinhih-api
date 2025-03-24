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
