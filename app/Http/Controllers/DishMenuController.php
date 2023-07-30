<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use Illuminate\Http\Request;

use App\Models\Calendar;
use App\Models\MonthCalendar;
use App\Models\User;

class DishMenuController extends Controller {
    protected function month_by(User $user, MonthCalendar $month_calendar, $date, $today) {
        $calendars = Calendar::periodBy($month_calendar->start_date, $month_calendar->end_date)->orderBy('date')->get();

        $day_calendar = $calendars->where('date', $date)->first();

        $reserve = $user->reserves()->enabled()->unCanceled()->where('date', '>=', $today ?? today())->orderBy('date')->first();

        return view('pages.dish_menu.index')
            ->with('day_calendar', $day_calendar)
            ->with('month_calendar', $month_calendar)
            ->with('calendars', $calendars)
            ->with('today', op($today)->format('Y-m-d'))
            ->with('reserve', $reserve)
        ;
    }

    //
    public function index(Request $request) {
        $user = $this->user();

        $date = today();

        $month_calendar = MonthCalendar::dateBy($date)->first();
        abort_if(!$month_calendar, 404, __('not_found.month_calender'));

        return $this->month_by($user, $month_calendar, $date, today());
    }

    public function date_at(Request $request, $date) {
        $user = $this->user();

        $month_calendar = MonthCalendar::dateBy($date)->first();
        abort_if(!$month_calendar, 404, __('not_found.month_calender'));

        return $this->month_by($user, $month_calendar, Carbon::parse($date), today());
    }
}
