<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\StoreRequest;
use App\Http\Resources\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Workspace;

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


    /**
     * @OA\Post(
     *     path="/booking/confirm",
     *     operationId="confirmBooking",
     *     tags={"Booking Api"},
     *     summary="Confirm a public booking",
     *     description="Creates a client, user, and appointment under a workspace.",
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"workspace_id", "date", "time", "first_name", "last_name", "email", "phone", "services", "locations"},
     *             @OA\Property(property="workspace_id", type="integer", example=64),
     *             @OA\Property(property="date", type="string", format="date", example="2025-07-01"),
     *             @OA\Property(property="time", type="string", format="HH:MM", example="14:00"),
     *             @OA\Property(property="first_name", type="string", example="John"),
     *             @OA\Property(property="last_name", type="string", example="Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="phone", type="string", example="01700000000"),
     *             @OA\Property(
     *                 property="services",
     *                 type="array",
     *                 @OA\Items(type="integer", example=1)
     *             ),
     *             @OA\Property(
     *                 property="locations",
     *                 type="array",
     *                 @OA\Items(type="integer", example=3)
     *             ),
     *             @OA\Property(property="description", type="string", example="Optional note")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Booking confirmed successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Appointment successfully booked"),
     *             @OA\Property(property="appointment", type="object",
     *                 @OA\Property(property="id", type="integer", example=101),
     *                 @OA\Property(property="workspace_id", type="integer", example=64),
     *                 @OA\Property(property="date", type="string", format="date", example="2025-07-01"),
     *                 @OA\Property(property="time", type="string", format="HH:MM", example="14:00"),
     *                 @OA\Property(property="description", type="string", example="Optional appointment note"),
     *                 @OA\Property(property="services", type="array", @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Consultation")
     *                 )),
     *                 @OA\Property(property="locations", type="array", @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=3),
     *                     @OA\Property(property="name", type="string", example="Dhaka Clinic")
     *                 )),
     *                 @OA\Property(property="attendees", type="array", @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=201),
     *                     @OA\Property(property="name", type="string", example="John Doe")
     *                 ))
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */

    public function confirmBooking(StoreRequest $request)
    {
        $workspace = Workspace::with(['clients'])->findOrFail($request->workspace_id);

        // Step 1: Prepare basic client data
        $clientData = $request->only(['first_name', 'last_name', 'email', 'phone']);
        $clientData['status'] = 'active';

        // Step 2: Get or create Client under this workspace
        $client = $workspace->clients()
            ->where('email', $request->email)
            ->first();

        if (!$client) {
            $client = $workspace->clients()->create($clientData);
        }

        // Step 3: Get or create User (global user model) by email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            $user = User::create([
                'email' => $request->email,
                'password' => bcrypt('tinhih_client'), // default password
                'role' => 'client',
            ]);
        }

        // Step 4: Create the appointment
        $request['attendees'] = [$user->id];

        $appointment = Appointment::create($request->only([
            'workspace_id',
            'date',
            'time',
            'description'
        ]));

        // Step 5: Attach relations
        $appointment->locations()->sync($request->locations);
        $appointment->services()->sync($request->services);
        $appointment->attendees()->sync($request->attendees);

        return response()->json([
            'message' => 'Appointment successfully booked',
            'appointment' => new AppointmentResource($appointment)
        ], 201);
    }
}
