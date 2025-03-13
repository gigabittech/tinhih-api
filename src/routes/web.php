<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::fallback(function (Request $request) {
    return response()->json([
        'success' => false,
        'message' => 'API endpoint not found. Please check the documentation.',
    ], 404);
});
