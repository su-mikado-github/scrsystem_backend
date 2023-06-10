<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use Illuminate\Http\Request;

use App\ReserveTypes;

use App\Models\Reserve;

class DiningHallController extends Controller {
    //
    public function index(Request $request, $date=null) {
        //
        $today = (isset($date) ? Carbon::parse($date) : today());

        $dining_hall_reserves = Reserve::with([ 'user' ])->enabled()->unCanceled()->dateBy($today)->typesBy([ ReserveTypes::VISIT_SOCCER, ReserveTypes::VISIT_NO_SOCCER ])->orderBy('time')->orderBy('id')->get();
        $lunchbox_reserves = Reserve::with([ 'user' ])->enabled()->unCanceled()->dateBy($today)->typesBy([ ReserveTypes::LUNCHBOX ])->orderBy('id')->get();

        return view('pages.dining_hall.index')
            ->with('today', $today)
            ->with('dining_hall_reserves', $dining_hall_reserves)
            ->with('lunchbox_reserves', $lunchbox_reserves)
        ;
    }
}
