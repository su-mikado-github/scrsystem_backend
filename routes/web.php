<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('/callbacks')->group(function() {
    Route::get('/line_login', [ App\Http\Controllers\CallbacksController::class, 'line_login' ]);
});

Route::prefix('/line')->group(function() {
    Route::post('/webhook', [ App\Http\Controllers\LineController::class, 'webhook' ]);
});

Route::middleware([ 'attest' ])->group(function() {

    Route::prefix('/checkin')->group(function() {
        Route::get('/', [ App\Http\Controllers\CheckinController::class, 'index' ])->name('checkin');
    });

    Route::prefix('/reserve')->group(function() {
        Route::get('/', [ App\Http\Controllers\ReserveController::class, 'index' ])->name('reserve');

        Route::prefix('/visit')->group(function() {
            Route::get('/', [ App\Http\Controllers\Reserve\VisitController::class, 'index' ])->name('reserve.visit');
        });

        Route::prefix('/lunchbox')->group(function() {
            Route::get('/', [ App\Http\Controllers\Reserve\LunchboxController::class, 'index' ])->name('reserve.lunchbox');
        });

        Route::prefix('/change')->group(function() {
            Route::get('/', [ App\Http\Controllers\Reserve\ChangeController::class, 'index' ])->name('reserve.change');
        });
    });

    Route::prefix('/mypage')->group(function() {
        Route::get('/', [ App\Http\Controllers\MypageController::class, 'index' ])->name('mypage');
        Route::put('/', [ App\Http\Controllers\MypageController::class, 'put' ]);
    });

    Route::prefix('/dish_menu')->group(function() {
        Route::get('/', [ App\Http\Controllers\DishMenuController::class, 'index' ])->name('dish_menu');
    });

    Route::prefix('/buy_ticket')->group(function() {
        Route::get('/', [ App\Http\Controllers\BuyTicketController::class, 'index' ])->name('buy_ticket');
    });
});

Route::get('/', function() {
    return view('pages.index');
});

// Route::any('/callbacks/{type?}', function($type) {
//     logger()->info('---[ CALLBACK: START ]---');
//     logger()->info($type);
//     logger()->info('■SERVER変数');
//     logger()->debug($_SERVER);
//     logger()->info('■リクエスト');
//     logger()->debug(request()->input());
//     logger()->info('---[ CALLBACK: END ]---');
//     return redirect('/');
// });
