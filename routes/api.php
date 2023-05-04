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
