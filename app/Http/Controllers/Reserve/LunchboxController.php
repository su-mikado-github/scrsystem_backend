<?php

namespace App\Http\Controllers\Reserve;

use Carbon\Carbon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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

        // 回数券の残数確認
        if ($user->last_ticket_count == 0) {
            return redirect()->route('buy_ticket')
                ->with([
                    'warning' => __('messages.warning.ticket_by_short'),
                    'backward' => route('reserve.lunchbox'),
                ])
            ;
        }

        $calendars = Calendar::periodBy($month_calendar->start_date, $month_calendar->end_date)->orderBy('date')->get();

        $previous_date = $today->copy()->subMonth();
        $next_date = $today->copy()->addMonth();

        $day_calendar = $calendars->where('date', $today)->first();

        return view('pages.reserve.lunchbox.index')
            ->with('day_calendar', $day_calendar)
            ->with('previous_date', $previous_date)
            ->with('next_date', $next_date)
            ->with('month_calendar', $month_calendar)
            ->with('calendars', $calendars)
        ;
    }

    public function post(Request $request, $date) {
        $user = $this->user();

        $rules = [
            'lunchbox_count' => [ 'required', 'integer', 'min:1' ],
        ];
        $this->try_validate($request->all(), $rules);

        $lunchbox_count = $request->input('lunchbox_count');

        // 購入回数券を引き当てる
        $valid_tickets = $user->valid_tickets()->validateBy()->orderBy('buy_dt')->get();
        $buy_ticket_ids = collect();
        $valid_ticket = null;
        $valid_ticket_count = 0;
        for ($i=0; $i<$lunchbox_count; $i++) {
            if ($valid_ticket_count == 0) {
                if ($valid_tickets->count() == 0) {
                    break;
                }

                $valid_ticket = $valid_tickets->shift();
                if (empty($valid_ticket)) {
                    break;
                }
                $valid_ticket_count = op($valid_ticket)->valid_ticket_count ?? 0;
            }
            $buy_ticket_ids->push($valid_ticket->buy_ticket_id);
            $valid_ticket_count --;
        }
        // logger()->debug(sprintf('%s(%s) => %s', __FILE__, __LINE__, print_r([ $buy_ticket_ids->count(), $lunchbox_count ], true)));
        if ($buy_ticket_ids->count() < $lunchbox_count) {
            logger()->debug(sprintf('%s(%s)', __FILE__, __LINE__));
            return redirect()->route('buy_ticket')
                ->withInput()
                ->with([
                    'warning' => __('messages.warning.ticket_by_short'),
                    'backward' => route('reserve.lunchbox', compact('date')),
                ])
            ;
        }

        return $this->trans(function() use($request, $date, $user, $buy_ticket_ids) {
            $reserve = new Reserve();
            $reserve->type = ReserveTypes::LUNCHBOX;
            $reserve->date = $date;
            // $reserve->time = null;
            // $reserve->end_time = null;
            $reserve->user_id = $user->id;
            $reserve->reserve_dt = now();
            $reserve->reserve_count = $request->input('lunchbox_count');
            // $reserve->is_table_share = Flags::OFF;
            $this->save($reserve, $user);

            foreach ($buy_ticket_ids as $buy_ticket_id) {
                $use_ticket = new UseTicket();
                $use_ticket->reserve_id = $reserve->id;
                $use_ticket->user_id = $user->id;
                $use_ticket->buy_ticket_id = $buy_ticket_id;
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
