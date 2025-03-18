<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::fallback(function (Request $request) {
    return response()->json([
        'success' => false,
        'message' => 'API endpoint not found. Please check the documentation.',
    ], 404);
});

Route::get('/api/documentation', function () {
    return view('swagger.index');  // Your Swagger UI view
});

Route::get('/api/docs', function () {
    return response()->json(json_decode(file_get_contents(storage_path('api-docs/api-docs.json'))));
});
