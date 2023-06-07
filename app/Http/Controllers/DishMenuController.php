<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use Illuminate\Http\Request;

use App\Models\Calendar;
use App\Models\MonthCalendar;

class DishMenuController extends Controller {
    //
    public function index(Request $request) {
        $year_month = $request->input('year_month', today()->format('Y-m'));
        abort_if(!preg_match('/^[0-9]{4}[\-][0-9]{2}/i', $year_month), 412, __('invalidate.fomrat.year_month'));

        list($year, $month) = explode('-', $year_month);

        $month_calendar = MonthCalendar::yearMonthBy($year, $month)->first();
        abort_if(!preg_match('/^[0-9]{4}[\-][0-9]{2}/i', $year_month), 404, __('not_found.month_calender'));

        $calendars = Calendar::periodBy($month_calendar->start_date, $month_calendar->end_date)->orderBy('date')->get();

        $date = $request->input('date', $month_calendar->date);

        $day_calendar = $calendars->where('date', Carbon::parse($date))->first();

        return view('pages.dish_menu.index')
            ->with('day_calendar', $day_calendar)
            ->with('month_calendar', $month_calendar)
            ->with('calendars', $calendars)
        ;
    }
}
