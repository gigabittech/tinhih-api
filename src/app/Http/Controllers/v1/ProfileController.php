<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\StoreRequest;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function updateProfile(StoreRequest $request)
    {
        try {
            // Assuming you have a Profile model and the user is authenticated
            $user = $request->user();
            $profile = $user->profile;

            // Update the profile with the validated data
            $profile->update($request->validated());

            return response()->json(['message' => 'Profile updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update profile'], 500);
        }
    }
}
