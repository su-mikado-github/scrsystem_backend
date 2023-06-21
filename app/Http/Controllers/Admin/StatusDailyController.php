<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StatusDailyController extends Controller {
    //
    public function index(Request $request, $date) {
        //
        return view('pages.admin.status.daily.index');
    }
}
