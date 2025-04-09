<?php

use App\Http\Controllers\v1\AppointmentController;
use App\Http\Controllers\v1\AuthController;
use App\Http\Controllers\v1\ClientController;
use App\Http\Controllers\v1\InvoiceController;
use App\Http\Controllers\v1\LocationController;
use App\Http\Controllers\v1\LocationTypeController;
use App\Http\Controllers\v1\ProfileController;
use App\Http\Controllers\v1\ServiceController;
use App\Http\Controllers\v1\TaxController;
use App\Http\Controllers\v1\TeamMemberController;
use App\Http\Controllers\v1\WorkspaceController;
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

        Route::get('/user', [AuthController::class, 'getUser']);

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

        Route::prefix('locations')->group(function () {
            Route::get('/', [LocationController::class, 'getLocations']);
            Route::get('/user', [LocationController::class, 'getUserLocations']);
            Route::get('/{id}', [LocationController::class, 'getLocation']);
            Route::post('/', [LocationController::class, 'createLocation']);
            Route::put('/{id}', [LocationController::class, 'updateLocation']);
            Route::delete('/{id}', [LocationController::class, 'deleteLocation']);
        });

        Route::prefix('services')->group(function () {
            Route::get('/', [ServiceController::class, 'getservices']);
            Route::get('/user', [ServiceController::class, 'getServicesByUser']);
            Route::get('/{id}', [ServiceController::class, 'getservice']);
            Route::post('/', [ServiceController::class, 'createservice']);
            Route::put('/{id}', [ServiceController::class, 'updateservice']);
            Route::delete('/{id}', [ServiceController::class, 'deleteservice']);
        });

        Route::prefix('members')->group(function () {
            Route::get('/', [TeamMemberController::class, 'getMembers']);
            Route::get('/{id}', [TeamMemberController::class, 'getMember']);
            Route::post('/', [TeamMemberController::class, 'createMember']);
            Route::put('/{id}', [TeamMemberController::class, 'updateMember']);
            Route::delete('/{id}', [TeamMemberController::class, 'deleteMember']);
        });

        Route::prefix('workspaces')->group(function () {
            Route::get('/', [WorkspaceController::class, 'getWorkspaces']);
            Route::get('/user', [WorkspaceController::class, 'getUserWorkspaces']);
            Route::put('/settings', [WorkspaceController::class, 'updateCurrentWorkspace']);
            Route::get('/{id}', [WorkspaceController::class, 'getWorkspace']);
            Route::post('/', [WorkspaceController::class, 'createWorkspace']);
            Route::post('/toggle', [WorkspaceController::class, 'toggleWorkspace']);
            Route::put('/{id}', [WorkspaceController::class, 'updateWorkspace']);
            Route::delete('/{id}', [WorkspaceController::class, 'deleteWorkspace']);
        });

        Route::prefix('appointments')->group(function () {
            Route::get('/', [AppointmentController::class, 'getAppointments']);
            Route::get('/{id}', [AppointmentController::class, 'getAppointment']);
            Route::post('/', [AppointmentController::class, 'createAppointment']);
            Route::put('/{id}', [AppointmentController::class, 'updateAppointment']);
            Route::delete('/{id}', [AppointmentController::class, 'deleteAppointment']);
        });

        Route::prefix('clients')->group(function () {
            Route::get('/', [ClientController::class, 'getClients']);
            Route::get('/{id}', [ClientController::class, 'getClient']);
            Route::post('/', [ClientController::class, 'createClient']);
            Route::put('/{id}', [ClientController::class, 'updateClient']);
            Route::delete('/{id}', [ClientController::class, 'deleteClient']);
        });

        Route::prefix('taxes')->group(function () {
            Route::get('/', [TaxController::class, 'getTaxes']);
            Route::get('/{id}', [TaxController::class, 'getTax']);
            Route::post('/', [TaxController::class, 'createTax']);
            Route::put('/{id}', [TaxController::class, 'updateTax']);
            Route::delete('/{id}', [TaxController::class, 'deleteTax']);
        });

        Route::prefix('invoices')->group(function () {
            Route::get('/', [InvoiceController::class, 'getInvoices']);
            Route::get('/{id}', [InvoiceController::class, 'getInvoice']);
            Route::post('/', [InvoiceController::class, 'createInvoice']);
            Route::put('/{id}', [InvoiceController::class, 'updateInvoice']);
            Route::delete('/{id}', [InvoiceController::class, 'deleteInvoice']);
            Route::patch('/{id}/mark-as-paid', [InvoiceController::class, 'markAsPaid']);
        });

        Route::post('/setup', [WorkspaceController::class, 'setupWorkspace'])->middleware('verifyWorkspaceSetup');
        Route::put('/profile/settings', [ProfileController::class, 'updateProfile']);
    });
});
