<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Appointment\StoreRequest;
use App\Http\Requests\Appointment\UpdateRequest;
use App\Http\Resources\Resources\AppointmentResource;
use App\Repository\AppointmentRepository;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function __construct(protected AppointmentRepository $repository) {}

    /**
     * @OA\Get(
     *     path="v1/appointments",
     *     summary="Get all appointments",
     *     description="Retrieves a list of appointments.",
     *     tags={"Appointments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         required=false,
     *         description="Filter appointments by user ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Appointments retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Appointments retrieved"),
     *             @OA\Property(property="appointments", type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="date", type="string", format="date", example="2025-03-27"),
     *                     @OA\Property(property="time", type="string", example="10:00 AM"),
     *                     @OA\Property(property="status", type="string", example="confirmed"),
     *                     @OA\Property(property="locations", type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="name", type="string", example="New York Office"),
     *                             @OA\Property(property="address", type="string", example="123 Main St, New York, NY")
     *                         )
     *                     ),
     *                     @OA\Property(property="services", type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="service_name", type="string", example="Consultation"),
     *                             @OA\Property(property="service_description", type="string", example="One-hour consultation with a specialist")
     *                         )
     *                     ),
     *                     @OA\Property(property="attendees", type="array",
     *                         @OA\Items(
     *                             type="string",
     *                             example="John Doe"
     *                         )
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
     *             @OA\Property(property="message", type="string", example="Something went wrong"),
     *             @OA\Property(property="errors", type="string", example="Error details")
     *         )
     *     )
     * )
     */


    public function getAppointments(Request $request)
    {
        try {
            return response()->json([
                'message' => 'Appointments retrieve successfull',
                'appointments' => AppointmentResource::collection($request->user()->workspaces()->where('active', 1)->first()->appointments)
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => 'Server error',
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="v1/appointments",
     *     summary="Create a new appointment",
     *     description="Creates a new appointment with associated locations, services, and attendees.",
     *     tags={"Appointments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="workspace_id", type="integer", example=1),
     *             @OA\Property(property="date", type="string", format="date", example="2025-03-27"),
     *             @OA\Property(property="time", type="string", example="10:00 AM"),
     *             @OA\Property(property="attendees", type="array", @OA\Items(type="string", example="John Doe")),
     *             @OA\Property(property="services", type="array", @OA\Items(type="integer", example=1)),
     *             @OA\Property(property="locations", type="array", @OA\Items(type="integer", example=1)),
     *             @OA\Property(property="description", type="string", example="Meeting with the client")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Appointment successfully booked",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Appointment successfully booked"),
     *             @OA\Property(property="appointment", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="date", type="string", format="date", example="2025-03-27"),
     *                 @OA\Property(property="time", type="string", example="10:00 AM"),
     *                 @OA\Property(property="status", type="string", example="confirmed"),
     *                 @OA\Property(property="locations", type="array", @OA\Items(type="string", example="New York Office")),
     *                 @OA\Property(property="services", type="array", @OA\Items(type="string", example="Consultation")),
     *                 @OA\Property(property="attendees", type="array", @OA\Items(type="string", example="John Doe"))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Something went wrong"),
     *             @OA\Property(property="errors", type="string", example="Error details")
     *         )
     *     )
     * )
     */

    public function createAppointment(StoreRequest $request)
    {
        try {
            $appoinement = $this->repository->create($request->validated());
            $appoinement->locations()->sync($request->locations);
            $appoinement->services()->sync($request->services);
            $appoinement->attendees()->sync($request->attendees);
            return response()->json([
                'message' => 'Appointment successfully booked',
                'appointment' => new AppointmentResource($appoinement)
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Server error',
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="v1/appointments/:id",
     *     summary="Update an existing appointment",
     *     description="Updates an existing appointment with new details.",
     *     tags={"Appointments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the appointment to update",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="workspace_id", type="integer", example=1),
     *             @OA\Property(property="date", type="string", format="date", example="2025-03-27"),
     *             @OA\Property(property="time", type="string", example="10:00 AM"),
     *             @OA\Property(property="attendees", type="array", @OA\Items(type="string", example="John Doe")),
     *             @OA\Property(property="services", type="array", @OA\Items(type="integer", example=1)),
     *             @OA\Property(property="locations", type="array", @OA\Items(type="integer", example=1)),
     *             @OA\Property(property="description", type="string", example="Updated meeting with the client")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Appointment successfully updated",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Appointment successfully updated"),
     *             @OA\Property(property="appointment", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="date", type="string", format="date", example="2025-03-27"),
     *                 @OA\Property(property="time", type="string", example="10:00 AM"),
     *                 @OA\Property(property="status", type="string", example="confirmed"),
     *                 @OA\Property(property="locations", type="array", @OA\Items(type="string", example="New York Office")),
     *                 @OA\Property(property="services", type="array", @OA\Items(type="string", example="Consultation")),
     *                 @OA\Property(property="attendees", type="array", @OA\Items(type="string", example="John Doe"))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Something went wrong"),
     *             @OA\Property(property="errors", type="string", example="Error details")
     *         )
     *     )
     * )
     */

    public function updateAppointment($id, UpdateRequest $request)
    {
        try {
            $appoinement = $this->repository->update($id, $request->validated());
            return response()->json([
                'message' => 'Appointment successfully booked',
                'appointment' => new AppointmentResource($appoinement)
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Server error',
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="v1/appointments/:id",
     *     summary="Get a single appointment",
     *     description="Retrieve details of a single appointment.",
     *     tags={"Appointments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the appointment to retrieve",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Appointment retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Appointment retrieve successful"),
     *             @OA\Property(property="appointment", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="date", type="string", format="date", example="2025-03-27"),
     *                 @OA\Property(property="time", type="string", example="10:00 AM"),
     *                 @OA\Property(property="status", type="string", example="confirmed"),
     *                 @OA\Property(property="locations", type="array", @OA\Items(type="string", example="New York Office")),
     *                 @OA\Property(property="services", type="array", @OA\Items(type="string", example="Consultation")),
     *                 @OA\Property(property="attendees", type="array", @OA\Items(type="string", example="John Doe"))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Appointment not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Appointment not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Something went wrong"),
     *             @OA\Property(property="errors", type="string", example="Error details")
     *         )
     *     )
     * )
     */

    public function getAppointment($id)
    {
        try {

            $appointment = $this->repository->find($id);
            if ($appointment) {

                return response()->json([
                    'message' => "Appointment retrieve successfull",
                    'appointment' => new AppointmentResource($appointment)

                ], 200);
            }
            return response()->json([
                'message' => 'Appointment not found'
            ], 404);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Server error',
            ], 500);
        }
    }


    /**
     * @OA\Delete(
     *     path="v1/appointments/:id",
     *     summary="Delete an appointment",
     *     description="Deletes an existing appointment by ID.",
     *     tags={"Appointments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Appointment ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Appointment deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Appointment deleted")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Server error")
     *         )
     *     )
     * )
     */
    public function deleteAppointment($id)
    {
        try {
            $this->repository->delete($id);
            return response()->json([
                'message' => 'Appointment deleted',
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => 'Server error',
            ], 500);
        }
    }
}
