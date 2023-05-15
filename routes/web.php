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
        Route::get('/complete', [ App\Http\Controllers\CheckinController::class, 'complete' ])->name('checkin.complete');
    });

    Route::prefix('/reserve')->group(function() {
        Route::get('/', [ App\Http\Controllers\ReserveController::class, 'index' ])->name('reserve');

        Route::prefix('/visit')->group(function() {
            Route::get('/{date?}', [ App\Http\Controllers\Reserve\VisitController::class, 'index' ])->name('reserve.visit')->where('date', '^[0-9]{4}-[0-9]{2}-[0-9]{2}');
        });

        Route::prefix('/lunchbox')->group(function() {
            Route::get('/{date?}', [ App\Http\Controllers\Reserve\LunchboxController::class, 'index' ])->name('reserve.lunchbox')->where('date', '^[0-9]{4}-[0-9]{2}-[0-9]{2}');
        });

        Route::prefix('/change')->group(function() {
            Route::get('/', [ App\Http\Controllers\Reserve\ChangeController::class, 'index' ])->name('reserve.change');
            Route::post('/{reserve_id}', [ App\Http\Controllers\Reserve\ChangeController::class, 'post' ])->where('reserve_id', '^[0-9]+$');
            Route::delete('/{reserve_id}', [ App\Http\Controllers\Reserve\ChangeController::class, 'delete' ])->where('reserve_id', '^[0-9]+$');
        });
    });

    Route::prefix('/mypage')->group(function() {
        logger()->debug(request()->method());
        Route::get('/', [ App\Http\Controllers\MypageController::class, 'index' ])->name('mypage');
        Route::post('/', [ App\Http\Controllers\MypageController::class, 'post' ]);
    });

    Route::prefix('/dish_menu')->group(function() {
        Route::get('/', [ App\Http\Controllers\DishMenuController::class, 'index' ])->name('dish_menu');
    });

    Route::prefix('/buy_ticket')->group(function() {
        Route::get('/', [ App\Http\Controllers\BuyTicketController::class, 'index' ])->name('buy_ticket');
        Route::post('/{ticket_id}', [ App\Http\Controllers\BuyTicketController::class, 'post' ])->where('ticket_id', '^[0-9]+$');
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
