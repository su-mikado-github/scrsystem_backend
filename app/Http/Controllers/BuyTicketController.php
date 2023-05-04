<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BuyTicketController extends Controller {
    //
    public function index() {
        return view('pages.buy_ticket.index');
    }
}
