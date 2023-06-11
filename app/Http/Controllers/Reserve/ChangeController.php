<?php

namespace App\Http\Controllers\Reserve;

use Carbon\Carbon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Flags;
use App\ReserveTypes;

use App\Models\Calendar;
use App\Models\EmptyState;
use App\Models\MonthCalendar;
use App\Models\Reserve;
use App\Models\TimeSchedule;
use App\Models\User;

class ChangeController extends Controller {
    //
    public function index(Request $request, $date=null) {
        $user = $this->user();

        $today = (isset($date) ? Carbon::parse($date) : today()->copy()->addDays());

        $reserve = $user->reserves()->where('date', '>=', $today)->orderBy('date')->first();
        if (isset($reserve)) {
            $today = $reserve->date;
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

        if (op($reserve)->type == ReserveTypes::LUNCHBOX) {
            return view('pages.reserve.change.index_lunchbox')
                ->with('reserve', $reserve)
                ->with('day_calendar', $day_calendar)
                ->with('previous_date', $previous_date)
                ->with('next_date', $next_date)
                ->with('month_calendar', $month_calendar)
                ->with('calendars', $calendars)
            ;
        }
        else {
            $time_schedules = ($user->affiliation_detail->is_soccer==Flags::ON ? TimeSchedule::soccer() : TimeSchedule::noSoccer())->orderBy('time')->get();
            $start_time = $time_schedules->min('time');
            $end_time = $time_schedules->max('time');

            $empty_states = EmptyState::dateBy($today)->timeRangeBy($start_time, $end_time)
                ->selectRaw('time, FLOOR(SUM(empty_seat_count) * 100 / SUM(seat_count)) as empty_seat_rate')
                ->groupBy('time')
                ->orderBy('time')
                ->get();

            return view('pages.reserve.change.index_visit')
                ->with('reserve', $reserve)
                ->with('day_calendar', $day_calendar)
                ->with('previous_date', $previous_date)
                ->with('next_date', $next_date)
                ->with('month_calendar', $month_calendar)
                ->with('calendars', $calendars)
                ->with('time_schedules', $time_schedules)
                ->with('empty_states', $empty_states)
            ;
        }
    }

    public function post(Request $request, $reserve_id) {
        return view('pages.reserve.change.post');
    }

    public function delete(Request $request, $reserve_id) {
        $user = $this->user();

        $reserve = Reserve::enabled()->find($reserve_id);
        abort_if(!$reserve, 404, __('messages.not_found.reserve'));

        if (isset($reserve->cancel_dt)) {
            return redirect()->action([ self::class, 'index' ], [ 'date'=>$reserve->date->format('Y-m-d') ])
                ->with('error', __('messages.error.canceled'));
        }
        else if (isset($reserve->checkin_dt)) {
            return redirect()->action([ self::class, 'index' ], [ 'date'=>$reserve->date->format('Y-m-d') ])
                ->with('error', __('messages.error.lunchbox_checkin'));
        }

        return $this->trans(function() use($request, $user, $reserve) {
            $reserve->cancel_dt = now();
            $this->save($reserve, $user);

            //　LINE通知
            $canceled_view = ($reserve->type==ReserveTypes::LUNCHBOX ? 'templates.line.lunchbox_canceled' : 'templates.line.visit_canceled');
            $message = view($canceled_view)->with('user', $user)->with('reserve', $reserve)->render();
            if (!$this->line_api()->push_messages($user->line_user->line_owner_id, [ $message ])) {
                DB::rollBack();
                return redirect()->action([ self::class, 'index' ], [ 'date'=>$date ])
                    ->with('error', __('messages.error.push_messages'));
            }

            $target_line_owner_id_list = User::with('line_user')->enabled()->where('id', '!=', $reserve->user_id)->get()
                ->map(function($target_user) {
                    return op($target_user->line_user)->line_owner_id;
                })
                ->filter(function($line_owner_id) {
                    return isset($line_owner_id);
                });
            if ($target_line_owner_id_list->count() > 0) {
                //　LINE通知
                $cancel_notify_view = ($reserve->type==ReserveTypes::LUNCHBOX ? 'templates.line.lunchbox_cancel_notify' : 'templates.line.visit_cancel_notify');
                $message = view($cancel_notify_view)->with('user', $user)->with('reserve', $reserve)->render();
                $result = $this->line_api()->push_multicast_messages($target_line_owner_id_list->toArray(), [ $message ]);
                foreach ($result as $line_owner_id => $is_success) {
                    if (!$is_success) {
                        logger()->warning(sprintf('[LINE USER ID: %s] LINE通知の送信に失敗しました。', $line_owner_id));
                    }
                }
            }

            return view('pages.reserve.change.delete')
                ->with('reserve', $reserve)
            ;
        });
    }
}
