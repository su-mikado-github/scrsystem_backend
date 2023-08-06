<?php

namespace App\Http\Controllers\Reserve;

use Carbon\Carbon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\DishTypes;
use App\Flags;
use App\ReserveTypes;

use App\Models\Calendar;
use App\Models\MonthCalendar;
use App\Models\TimeSchedule;
use App\Models\DailyDishMenu;
use App\Models\Reserve;
use App\Models\UseTicket;
use App\Models\ValidTicket;

class LunchboxController extends Controller {
    //
    public function index(Request $request, $date=null) {
        $user = $this->user();

        if (isset($date)) {
            $today = Carbon::parse($date);
        }
        else {
            $today = today()->copy()->addDays(2);
            $daily_dish_types = DailyDishMenu::dishTypesBy([ DishTypes::LUNCHBOX, DishTypes::BOUT_LUNCHBOX ])->where('date', '>=', $today)
                ->orderBy('date')->orderBy('dish_type')
                ->first();
            if (isset($daily_dish_types)) {
                $today = $daily_dish_types->date;
            }
        }

        $month_calendar = MonthCalendar::yearMonthBy($today->year, $today->month)->first();
        abort_if(!$month_calendar, 404, __('not_found.month_calender'));

        $calendars = Calendar::periodBy($month_calendar->start_date, $month_calendar->end_date)->orderBy('date')->get();

        $previous_date = $today->copy()->subMonth();
        $next_date = $today->copy()->addMonth();

        $day_calendar = $calendars->where('date', $today)->first();

        $reserve = $day_calendar->reserves()->enabled()->lunchboxBy()->unCanceled()->userBy($user)->first();

        $time_schedules = TimeSchedule::lunchbox()->orderBy('time')->get();

        return view('pages.reserve.lunchbox.index')
            ->with('day_calendar', $day_calendar)
            ->with('reserve', $reserve)
            ->with('previous_date', $previous_date)
            ->with('next_date', $next_date)
            ->with('month_calendar', $month_calendar)
            ->with('calendars', $calendars)
            ->with('time_schedules', $time_schedules)
        ;
    }

    public function post(Request $request, $date) {
        $user = $this->user();

        $rules = [
            'lunchbox_count' => [ 'required', 'integer', 'min:1' ],
            'time' => [ 'required', 'string', Rule::exists('time_schedules', 'time')->where('type', ReserveTypes::LUNCHBOX)->where('is_delete', Flags::OFF) ],
        ];
        $this->try_validate($request->all(), $rules);

        $lunchbox_count = $request->input('lunchbox_count');

        return $this->trans(function() use($request, $date, $user, $lunchbox_count /*, $buy_ticket_ids */) {
            $reserve = new Reserve();
            $reserve->type = ReserveTypes::LUNCHBOX;
            $reserve->date = $date;
            $reserve->time = $request->input('time');
            $reserve->user_id = $user->id;
            $reserve->reserve_dt = now();
            $reserve->reserve_count = $lunchbox_count;
            $this->save($reserve, $user);

            for ($i=0; $i<$lunchbox_count; $i++) {
                $use_ticket = new UseTicket();
                $use_ticket->reserve_id = $reserve->id;
                $use_ticket->user_id = $user->id;
                $use_ticket->use_dt = now();
                $this->save($use_ticket, $user);
            }

            $reserve = $reserve->fresh();

            //　LINE通知
            $message = view('templates.line.lunchbox_reserved')->with('user', $user)->with('reserve', $reserve)->render();
            if (!$this->line_api()->push_messages($user->line_user->line_owner_id, [ $message ])) {
                DB::rollBack();
                return redirect()->action([ self::class, 'index' ], [ 'date'=>$date ])
                    ->with('error', __('messages.error.push_messages'));
            }

            return view('pages.reserve.lunchbox.post')
                ->with('reserve', $reserve)
            ;
        });
    }
}
