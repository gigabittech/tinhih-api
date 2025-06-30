<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::fallback(function (Request $request) {
    return response()->json([
        'message' => 'API endpoint not found. Please check the documentation.',
        'link' => route('api-docs') // or any other URL
    ], 404);
});

Route::get('v1/documentation', function () {
    return view('swagger.index');  // Your Swagger UI view
})->name('api-docs');

Route::get('v1/api/docs', function () {
    return response()->json(json_decode(file_get_contents(storage_path('api-docs/api-docs.json'))));
})->name('api.docs');
