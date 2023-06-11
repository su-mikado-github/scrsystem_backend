<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AttestController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
// asset()やurl()がhttpsで生成される
URL::forceScheme('https');

// Route::prefix('/attest')->group(function() {
//     Route::get('/login/{token?}', [ AttestController::class, 'login' ]);
//     Route::get('/line_callback', [ AttestController::class, 'line_callback' ]);
// });

Route::prefix('/file')->group(function() {
    Route::post('/upload', [ App\Http\Controllers\FileController::class, 'upload' ])->name("api.file_upload");
});

// Route::prefix('/checkin')->group(function() {
//     Route::get('/token', [ App\Http\Controllers\FileController::class, 'get_token' ])->name("api.checkin.get_token");
// });

