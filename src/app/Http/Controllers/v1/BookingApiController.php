<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Http\Request;

class BookingApiController extends Controller
{
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
