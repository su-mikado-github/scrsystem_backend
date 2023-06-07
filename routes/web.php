<?php

use Illuminate\Support\Facades\Route;

use App\DishTypes;

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

Route::prefix('/dining_hall')->group(function() {
    Route::get('/', [ App\Http\Controllers\DiningHallController::class, 'index' ]);
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
            Route::post('/{date}', [ App\Http\Controllers\Reserve\VisitController::class, 'post' ])->name('reserve.visit')->where('date', '^[0-9]{4}-[0-9]{2}-[0-9]{2}');
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

Route::prefix('/admin')->middleware('auth')->middleware('can:is_admin')->group(function() {

    Route::prefix('/dish_menu')->group(function() {
        Route::get('/{dish_type_key?}', [ App\Http\Controllers\Admin\DishMenuController::class, 'index' ])->name('admin.dish_menu')->where('dish_type_key', sprintf('^(%s)$', implode('|', array_map(function($dish_type) { return $dish_type->key; }, DishTypes::values()))));
        Route::post('/{dish_type_key?}/upload', [ App\Http\Controllers\Admin\DishMenuController::class, 'post_upload' ])->where('dish_type_key', sprintf('^(%s)$', implode('|', array_map(function($dish_type) { return $dish_type->key; }, DishTypes::values()))));
        // foreach (DishTypes::values() as $dish_type) {
        //     Route::get(sprintf('/%s', $dish_type->key), [ App\Http\Controllers\Admin\DishMenuController::class, $dish_type->key ])->name(sprintf('admin.dish_menu.%s', $dish_type->key));
        // }
    });

    Route::get('/', [ App\Http\Controllers\AdminController::class, 'index' ])->name('admin');
});

Route::prefix('/login')->group(function() {
    Route::prefix('/password_reset')->group(function() {
        Route::get('/', [ App\Http\Controllers\PasswordResetController::class, 'index' ])->name('login.password_reset');
        Route::post('/', [ App\Http\Controllers\PasswordResetController::class, 'post' ]);
    });

    Route::get('/', [ App\Http\Controllers\LoginController::class, 'index' ])->name('login');
    Route::post('/', [ App\Http\Controllers\LoginController::class, 'post' ]);
});

Route::get('/logout', [ App\Http\Controllers\LoginController::class, 'logout' ])->name('logout');

// Route::prefix('/file')->group(function() {
//     Route::post('/upload', [ App\Http\Controllers\FileController::class, 'upload' ])->name("file_upload");
// });

//セッション維持（暫定）
Route::get('/ping', function() { return view('pages.ping'); });

Route::get('/', [ App\Http\Controllers\RootController::class, 'index' ]);
