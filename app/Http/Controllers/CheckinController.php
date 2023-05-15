<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckinController extends Controller {
    //
    public function index() {
        return view('pages.checkin.index');
    }

    public function complete() {
        return view('pages.checkin.complete');
    }
}
