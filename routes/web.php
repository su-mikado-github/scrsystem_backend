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
    Route::get('/{date?}', [ App\Http\Controllers\DiningHallController::class, 'index' ])->name('dining_hall')->where('date', '^[0-9]{4}-[0-9]{2}-[0-9]{2}');
});

Route::middleware([ 'attest' ])->group(function() {

    Route::prefix('/checkin')->group(function() {
        Route::get('/{date?}', [ App\Http\Controllers\CheckinController::class, 'index' ])->name('checkin')->where('date', '^[0-9]{4}-[0-9]{2}-[0-9]{2}');
        Route::post('/{reserve_id}', [ App\Http\Controllers\CheckinController::class, 'post' ])->where('reserve_id', '^[0-9]+$');
        // Route::get('/token', [ App\Http\Controllers\CheckinController::class, 'get_token' ])->name('checkin.token');
    });

    Route::prefix('/reserve')->group(function() {
        Route::get('/', [ App\Http\Controllers\ReserveController::class, 'index' ])->name('reserve');

        Route::prefix('/visit')->group(function() {
            Route::get('/{date?}', [ App\Http\Controllers\Reserve\VisitController::class, 'index' ])->name('reserve.visit')->where('date', '^[0-9]{4}-[0-9]{2}-[0-9]{2}');
            Route::post('/{date}', [ App\Http\Controllers\Reserve\VisitController::class, 'post' ])->where('date', '^[0-9]{4}-[0-9]{2}-[0-9]{2}');
        });

        Route::prefix('/lunchbox')->group(function() {
            Route::get('/{date?}', [ App\Http\Controllers\Reserve\LunchboxController::class, 'index' ])->name('reserve.lunchbox')->where('date', '^[0-9]{4}-[0-9]{2}-[0-9]{2}');
            Route::post('/{date}', [ App\Http\Controllers\Reserve\LunchboxController::class, 'post' ])->where('date', '^[0-9]{4}-[0-9]{2}-[0-9]{2}');
        });

        Route::prefix('/change')->group(function() {
            Route::get('/{date?}', [ App\Http\Controllers\Reserve\ChangeController::class, 'index' ])->name('reserve.change')->where('date', '^[0-9]{4}-[0-9]{2}-[0-9]{2}');
            Route::post('/{reserve_id}/visit', [ App\Http\Controllers\Reserve\ChangeController::class, 'post_visit' ])->where('reserve_id', '^[0-9]+$');
            Route::post('/{reserve_id}/lunchbox', [ App\Http\Controllers\Reserve\ChangeController::class, 'post_lunchbox' ])->where('reserve_id', '^[0-9]+$');
            Route::delete('/{reserve_id}', [ App\Http\Controllers\Reserve\ChangeController::class, 'delete' ])->where('reserve_id', '^[0-9]+$');
        });
    });

    Route::prefix('/mypage')->group(function() {
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

    Route::prefix('/dish_menus')->group(function() {
        Route::get('/{dish_type_key?}', [ App\Http\Controllers\Admin\DishMenusController::class, 'index' ])->name('admin.dish_menus')->where('dish_type_key', sprintf('^(%s)$', implode('|', array_map(function($dish_type) { return $dish_type->key; }, DishTypes::values()))));
        Route::post('/{dish_type_key?}/upload', [ App\Http\Controllers\Admin\DishMenusController::class, 'post_upload' ])->where('dish_type_key', sprintf('^(%s)$', implode('|', array_map(function($dish_type) { return $dish_type->key; }, DishTypes::values()))));
    });

    Route::prefix('/users')->group(function() {
        Route::get('/', [ App\Http\Controllers\Admin\UsersController::class, 'index' ])->name('admin.users');
        Route::get('/download', [ App\Http\Controllers\Admin\UsersController::class, 'download' ])->name('admin.users.download');
        Route::get('/{user_id}', [ App\Http\Controllers\Admin\UserController::class, 'index' ])->name('admin.user')->where('user_id', '^[0-9]+$');
        Route::delete('/{user_id}', [ App\Http\Controllers\Admin\UserController::class, 'delete' ])->name('admin.user.delete')->where('user_id', '^[0-9]+$');
    });

    Route::prefix('/staffs')->group(function() {
        Route::get('/', [ App\Http\Controllers\Admin\StaffsController::class, 'index' ])->name('admin.staffs');
        Route::post('/', [ App\Http\Controllers\Admin\StaffController::class, 'post' ]);
        Route::put('/{user_id}', [ App\Http\Controllers\Admin\StaffController::class, 'put' ]);
        Route::delete('/{user_id}', [ App\Http\Controllers\Admin\StaffController::class, 'delete' ]);
        // Route::get('/download', [ App\Http\Controllers\Admin\UsersController::class, 'download' ])->name('admin.users.download');
        // Route::get('/{user_id}', [ App\Http\Controllers\Admin\UserController::class, 'index' ])->name('admin.user')->where('user_id', '^[0-9]+$');
        // Route::delete('/{user_id}', [ App\Http\Controllers\Admin\UserController::class, 'delete' ])->name('admin.user.delete')->where('user_id', '^[0-9]+$');
    });

    Route::prefix('/password_change')->group(function() {
        Route::get('/', [ App\Http\Controllers\Admin\PasswordChangeController::class, 'index' ])->name('admin.password_change');
        // Route::post('/{dish_type_key?}/upload', [ App\Http\Controllers\Admin\DishMenusController::class, 'post_upload' ])->where('dish_type_key', sprintf('^(%s)$', implode('|', array_map(function($dish_type) { return $dish_type->key; }, DishTypes::values()))));
    });



    Route::get('/', [ App\Http\Controllers\AdminController::class, 'index' ])->name('admin');
});

Route::prefix('/login')->group(function() {
    Route::prefix('/password_reset')->group(function() {
        Route::get('/', [ App\Http\Controllers\PasswordResetController::class, 'index' ])->name('login.password_reset');
        Route::patch('/', [ App\Http\Controllers\PasswordResetController::class, 'patch' ]);
    });

    Route::get('/', [ App\Http\Controllers\LoginController::class, 'index' ])->name('login');
    Route::post('/', [ App\Http\Controllers\LoginController::class, 'post' ]);
});

Route::get('/error', function() { return view('pages.error'); })->name('error');

Route::prefix('/new_password')->group(function() {
    Route::get('/{reset_token}', [ App\Http\Controllers\NewPasswordController::class, 'index' ])->name('new_password');
    Route::patch('/{user_id}/{reset_token}', [ App\Http\Controllers\NewPasswordController::class, 'patch' ])->where('user_id', '^[0-9]+$');
});

Route::get('/logout', [ App\Http\Controllers\LoginController::class, 'logout' ])->name('logout');

// Route::prefix('/file')->group(function() {
//     Route::post('/upload', [ App\Http\Controllers\FileController::class, 'upload' ])->name("file_upload");
// });

//セッション維持（暫定）
Route::get('/ping', function() { return view('pages.ping'); });

Route::get('/', [ App\Http\Controllers\RootController::class, 'index' ]);
