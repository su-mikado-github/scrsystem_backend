<?php

namespace App\Http\Controllers\Reserve;

use Carbon\Carbon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Flags;
use App\ReserveTypes;
use App\DishTypes;

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
        $user = $this->user();

        $reserve = Reserve::enabled()->find($reserve_id);
        abort_if(!$reserve, 404, __('messages.not_found.reserve'));

        if (isset($reserve->cancel_dt)) {
            return redirect()->action([ self::class, 'index' ], [ 'date'=>$reserve->date->format('Y-m-d') ])
                ->withInput()
                ->with('error', __('messages.error.canceled'));
        }
        else if (isset($reserve->checkin_dt)) {
            return redirect()->action([ self::class, 'index' ], [ 'date'=>$reserve->date->format('Y-m-d') ])
                ->withInput()
                ->with('error', __('messages.error.lunchbox_checkin'));
        }

        // 予約変更が可能な期限のチェック
        if (today()->copy()->addDays(2) > $reserve->date) {
            return redirect()->action([ self::class, 'index' ], [ 'date'=>$reserve->date->format('Y-m-d') ])
                ->withInput()
                ->with('error', __('messages.error.change_limit'));
        }

        // バリデーション
        $rules = [
            'new_date' => [ 'required', 'date', 'after_or_equal:' . today()->copy()->addDays(2)->format('Y-m-d') ],
        ];
        $messages = [
            'new_date.required' => '変更先の日付は必須です。',
            'new_date.date' => '正しい日付を入力してください。',
            'new_date.after_or_equal' => '変更先の日付は２日後以降にしてください。',
        ];
        $this->try_validate($request->all(), $rules, $messages);

        $new_date = Carbon::parse($request->input('new_date'));
        $calendar = Calendar::dateBy($new_date)->first();
        abort_if(!$calendar, 404, __('messages.not_found.calendar'));

        // 同一日チェック
        if ($new_date == $reserve->date) {
            return redirect()->action([ self::class, 'index' ], [ 'date'=>$reserve->date->format('Y-m-d') ])
                ->withInput()
                ->with('error', __('messages.error.same_date'));
        }

        // 予約の重複チェック
        $reserve_types = ($reserve->type == ReserveTypes::LUNCHBOX ? [ ReserveTypes::LUNCHBOX ] : [ ReserveTypes::VISIT_SOCCER, ReserveTypes::VISIT_NO_SOCCER ]);
        $is_reserve_exists = Reserve::dateBy($new_date)->userBy($user)->typesBy($reserve_types)->enabled()->unCanceled()->exists();
        if ($is_reserve_exists) {
            return redirect()->action([ self::class, 'index' ], [ 'date'=>$reserve->date->format('Y-m-d') ])
                ->withInput()
                ->with('error', __('messages.error.reserve_exists'));
        }

        // 料理メニューの設定有無のチェック
        $dish_types = ($reserve->type == ReserveTypes::LUNCHBOX ? [ DishTypes::LUNCHBOX, DishTypes::BOUT_LUNCHBOX ] : [ DishTypes::DINING_HALL ]);
        $is_dish_menu_exists = $calendar->dish_menus()->dishTypesBy($dish_types)->exists();
        if (!$is_dish_menu_exists) {
            return redirect()->action([ self::class, 'index' ], [ 'date'=>$reserve->date->format('Y-m-d') ])
                ->withInput()
                ->with('error', __('messages.error.no_dish_menu'));
        }

        return $this->trans(function() use($request, $user, $reserve, $calendar) {
            $reserve->date = $calendar->date;
            $this->save($reserve, $user);

            // LINE通知
            $canceled_view = ($reserve->type==ReserveTypes::LUNCHBOX ? 'templates.line.lunchbox_changed' : 'templates.line.visit_changed');
            $message = view($canceled_view)->with('user', $user)->with('reserve', $reserve)->render();
            if (!$this->line_api()->push_messages($user->line_user->line_owner_id, [ $message ])) {
                DB::rollBack();
                return redirect()->action([ self::class, 'index' ], [ 'date'=>$date ])
                    ->withInput()
                    ->with('error', __('messages.error.push_messages'));
            }

            $reserve = $reserve->fresh();
            return view('pages.reserve.change.post')
                ->with('reserve', $reserve)
            ;
        });
    }

    public function delete(Request $request, $reserve_id) {
        $user = $this->user();

        $reserve = Reserve::enabled()->find($reserve_id);
        abort_if(!$reserve, 404, __('messages.not_found.reserve'));

        if (today()->copy()->addDays(2) > $reserve->date) {
            return redirect()->action([ self::class, 'index' ], [ 'date'=>$reserve->date->format('Y-m-d') ])
                ->with('error', __('messages.error.cancel_limit'));
        }

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
