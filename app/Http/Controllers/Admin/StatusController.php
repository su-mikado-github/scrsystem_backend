<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Flags;
use App\ReserveTypes;

use App\Models\MonthCalendar;
use App\Models\Calendar;
use App\Models\User;

class StatusController extends Controller {
    //
    public function index(Request $request) {
        $year_month = $request->input('year_month', today()->format('Y-m'));
        list($year, $month) = explode('-', $year_month);

        $month_calendar = MonthCalendar::yearMonthBy($year, $month)->first();
        abort_if(!$month_calendar, 404, __('messages.not_found.month_calender'));

        $calendars = Calendar::yearMonthBy($year, $month)->enabled()
            ->selectRaw(<<<END_SELECT
calendars.*,
(
    SELECT COUNT(*)
    FROM users
    WHERE
        (users.regist_date <= calendars.date)
        AND ((users.unregist_date IS NULL) OR users.unregist_date >= calendars.date)
) AS user_count,
(
    SELECT COUNT(*)
    FROM users
        INNER JOIN affiliation_details ON (
            affiliation_details.id = users.affiliation_detail_id
            AND affiliation_details.is_soccer = ?
        )
    WHERE
        (users.regist_date<=calendars.date)
        AND ((users.unregist_date IS NULL) OR users.unregist_date >= calendars.date)
) AS soccer_user_count,
IFNULL((
    SELECT SUM(reserves.reserve_count)
    FROM reserves
    WHERE
        reserves.is_delete = ?
        AND reserves.date = calendars.date
        AND reserves.type IN (?)
), 0) AS soccer_reserve_count,
(
    SELECT COUNT(*)
    FROM reserves
        INNER JOIN users ON (
            users.id = reserves.user_id
        )
        INNER JOIN affiliation_details ON (
            affiliation_details.id = users.affiliation_detail_id
            AND affiliation_details.is_soccer = ?
        )
    WHERE
        reserves.is_delete = ?
        AND reserves.date = calendars.date
        AND reserves.type IN (?)
) AS lunchbox_reserve_count


END_SELECT
, [ Flags::ON, Flags::OFF, ReserveTypes::VISIT_SOCCER, Flags::ON, Flags::OFF, ReserveTypes::LUNCHBOX ])
            ->orderBy('date')
            ->get();
        logger()->debug('calendar_users');
        logger()->debug($calendars);

        return view('pages.admin.status.index')
            ->with('month_calendar', $month_calendar)
            ->with('calendars', $calendars)
        ;
    }
}
