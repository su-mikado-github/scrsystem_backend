<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RootController extends Controller {
    //
    public function index(Request $request) {
        return view('pages.index');
    }
}
