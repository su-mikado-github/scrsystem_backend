<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DishMenuController extends Controller {
    //
    public function index() {
        return view('pages.dish_menu.index');
    }
}
