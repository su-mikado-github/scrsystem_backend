<?php

namespace App\Http\Controllers\Reserve;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Flags;

use App\Models\TimeSchedule;

class ChangeController extends Controller {
    //
    public function index() {
        $user = $this->user();

        $time_schedules = ($user->affiliation_detail->is_soccer==Flags::ON ? TimeSchedule::soccer() : TimeSchedule::noSoccer())->orderBy('time')->get();

        return view('pages.reserve.change.index')
            ->with('time_schedules', $time_schedules)
        ;
    }

    public function post(Request $request, $reserve_id) {
        return view('pages.reserve.change.post');
    }

    public function delete(Request $request, $reserve_id) {
        return view('pages.reserve.change.delete');
    }
}
