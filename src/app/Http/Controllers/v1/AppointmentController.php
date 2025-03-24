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
