<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\StoreRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{

    /**
     * @OA\Put(
     *     path="/profile/settings",
     *     summary="Update authenticated user's profile",
     *     description="Updates the profile of the currently authenticated user.",
     *     operationId="updateProfile",
     *     tags={"Profile"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"first_name","last_name"},
     *             @OA\Property(property="first_name", type="string", example="John"),
     *             @OA\Property(property="last_name", type="string", example="Doe"),
     *             @OA\Property(property="phone", type="string", example="xxxxx"),
     *             @OA\Property(property="address", type="string", example="US"),
     *             @OA\Property(property="dob", type="string", example="2025-04-09"),
     *             @OA\Property(property="time_zone", type="string", example="America/New_York"),
     *             @OA\Property(property="locale", type="string", example="en-US"),
     *             @OA\Property(property="avatar", type="string", example="profile image link"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Profile updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Profile updated successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to update profile",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Failed to update profile")
     *         )
     *     )
     * )
     */

    public function updateProfile(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            // Assuming you have a Profile model and the user is authenticated
            $user = $request->user();
            $profile = $user->profile;

            // Update the profile with the validated data
            $profile->update($request->validated());

            $profile = $user->profile;
            DB::commit();
            return response()->json(['message' => 'Profile updated successfully']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to update profile'], 500);
        }
    }
}
