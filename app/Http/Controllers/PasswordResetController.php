<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PasswordResetController extends Controller {
    //
    public function index() {
        return view('pages.login.password_reset.index');
    }

    public function post(Request $request) {


        return view('pages.login.password_reset.post');
    }
}
