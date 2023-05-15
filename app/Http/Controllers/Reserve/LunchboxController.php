<?php

namespace App\Http\Controllers\Reserve;

use Carbon\Carbon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Flags;

use App\Models\Calendar;
use App\Models\TimeSchedule;

class LunchboxController extends Controller {
    //
    public function index(Request $request, $date=null) {
        $user = $this->user();

        $today = (isset($date) ? Carbon::parse($date) : today());
        $start_date = transform(Calendar::find_last_sunday($date), function($calendar) { return Carbon::parse($calendar->date); });
        abort_if(!$start_date, 400, __('messages.not_found.calendar'));
        $end_date = $start_date->copy()->addDay(6);

        $dates = Calendar::range($start_date, $end_date)->orderBy('date')->get();

        $time_schedules = TimeSchedule::lunchbox()->orderBy('time')->get();

        return view('pages.reserve.lunchbox.index')
            ->with('start_date', $start_date)
            ->with('end_date', $end_date)
            ->with('dates', $dates)
            ->with('time_schedules', $time_schedules)
        ;
    }
}
