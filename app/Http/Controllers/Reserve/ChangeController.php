<?php

namespace App\Http\Controllers\Reserve;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChangeController extends Controller {
    //
    public function index() {
        return view('pages.reserve.change.index');
    }
}
