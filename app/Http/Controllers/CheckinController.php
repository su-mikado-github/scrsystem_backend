<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use Illuminate\Http\Request;

use App\Models\Reserve;

class CheckinController extends Controller {
    //
    public function index(Request $request, $date=null) {
        $user = $this->user();

        $today = (isset($date) ? Carbon::parse($date) : today());
        $reserve = $user->reserves()->dateBy($today)->unCanceled()->noCheckin()->first();

        return view('pages.checkin.index')
            ->with('reserve', $reserve)
        ;
    }

    public function complete() {
        return view('pages.checkin.post');
    }
}
