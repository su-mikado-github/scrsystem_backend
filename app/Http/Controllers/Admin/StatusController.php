<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\MonthCalendar;
use App\Models\Calendar;

class StatusController extends Controller {
    //
    public function index(Request $request) {
        $year_month = $request->input('year_month', today()->format('Y-m'));
        list($year, $month) = explode('-', $year_month);

        $month_calendar = MonthCalendar::yearMonthBy($year, $month)->first();
        abort_if(!$month_calendar, 404, __('messages.not_found.month_calender'));

        $calendars = Calendar::yearMonthBy($year, $month)->enabled()->orderBy('date')->get();

        return view('pages.admin.status.index')
            ->with('month_calendar', $month_calendar)
            ->with('calendars', $calendars)
        ;
    }
}
