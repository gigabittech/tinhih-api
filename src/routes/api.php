<?php

use App\Http\Controllers\v1\AuthController;
use App\Http\Controllers\v1\LocationTypeController;
use App\Models\LocationType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::fallback(function (Request $request) {
    return response()->json([
        'success' => false,
        'message' => 'API endpoint not found. Please check the documentation.',
    ], 404);
});

Route::prefix('v1')->group(function () {
    Route::get('/', function () {
        return response()->json([
            'message' => "Api working ..."
        ]);
    });

    // Auth Routes
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/register', [AuthController::class, 'register']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        // Auth Route
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        // Appointment Booking Routes
        Route::prefix('locations')->group(function () {
            Route::get('/', function () {
                return response()->json(['message' => 'Locations']);
            });
        });

        // Appointment Booking Routes
        Route::prefix('appointment')->group(function () {
            Route::get('/', function () {
                return response()->json(['message' => 'Appointments']);
            });
        });

        // Providers routes
        Route::middleware('role:provider')->prefix('provider')->group(function () {
            Route::get('/dashboard', function () {
                return response()->json(['message' => 'provider Dashboard']);
            });
        });

        // Clients route 
        Route::middleware('role:client')->prefix('client')->group(function () {
            Route::get('/dashboard', function () {
                return response()->json(['message' => 'Client Dashboard']);
            });
        });

        // Admin Routes
        Route::middleware('role:admin')->prefix('admin')->group(function () {
            Route::get('/dashboard', function () {
                return response()->json(['message' => 'Admin Dashboard']);
            });
        });

        // Super admin routes
        Route::middleware('role:super_admin')->prefix('super_admin')->group(function () {
            Route::get('/dashboard', function () {
                return response()->json(['message' => 'Super Admin Dashboard']);
            });
        });

        Route::prefix('location_types')->group(function () {
            Route::get('/', [LocationTypeController::class, 'getLocationTypes']);
            Route::get('/{id}', [LocationTypeController::class, 'getLocationType']);
            Route::post('/', [LocationTypeController::class, 'createLocationType']);
            Route::put('/{id}', [LocationTypeController::class, 'updateLocationType']);
            Route::delete('/{id}', [LocationTypeController::class, 'deleteLocationType']);
        });
    });
});
