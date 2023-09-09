<?php

namespace App\Http\Controllers\Reserve;

use Carbon\Carbon;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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

        $reserves = $user->reserves()->enabled()->unCanceled()->where('date', '>=', $today)->dateOrdered()->timeOrdered()->typeOrdered()->get();
        $reserve = $reserves->first();
        if (isset($reserve)) {
            $today = $reserve->date;
        }

        $other_reserve = $reserves->where('date', '=', $today)->where('id', '!=', op($reserve)->id)->first();

        $month_calendar = MonthCalendar::yearMonthBy($today->year, $today->month)->first();
        abort_if(!$month_calendar, 404, __('not_found.month_calender'));

        $calendars = Calendar::periodBy($month_calendar->start_date, $month_calendar->end_date)->orderBy('date')->get();

        $previous_date = $today->copy()->subMonth();
        $next_date = $today->copy()->addMonth();

        $day_calendar = $calendars->where('date', $today)->first();

        if (empty($reserve)) {
            return view('pages.reserve.change.index')
                ->with('day_calendar', $day_calendar)
                ->with('previous_date', $previous_date)
                ->with('next_date', $next_date)
                ->with('month_calendar', $month_calendar)
                ->with('calendars', $calendars)
            ;
        }
        else if (op($reserve)->type == ReserveTypes::LUNCHBOX) {
            $time_schedules = TimeSchedule::lunchbox()->enabled()->orderBy('time')->get();

            return view('pages.reserve.change.index_lunchbox')
                ->with('reserve', $reserve)
                ->with('other_reserve', $other_reserve)
                ->with('day_calendar', $day_calendar)
                ->with('previous_date', $previous_date)
                ->with('next_date', $next_date)
                ->with('month_calendar', $month_calendar)
                ->with('calendars', $calendars)
                ->with('time_schedules', $time_schedules)
            ;
        }
        else {
            $time_schedules = ($user->affiliation_detail->is_soccer==Flags::ON ? TimeSchedule::soccer() : TimeSchedule::noSoccer())->enabled()->orderBy('time')->get();
            $start_time = $time_schedules->min('time');
            $end_time = $time_schedules->max('time');

            $empty_states = EmptyState::dateBy($today)->timeRangeBy($start_time, $end_time)
                ->selectRaw('time, FLOOR(SUM(empty_seat_count) * 100 / SUM(seat_count)) as empty_seat_rate')
                ->groupBy('time')
                ->orderBy('time')
                ->get();

            return view('pages.reserve.change.index_visit')
                ->with('reserve', $reserve)
                ->with('other_reserve', $other_reserve)
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

    public function reserve(Request $request, $reserve_id) {
        $user = $this->user();

        $reserve = Reserve::enabled()->find($reserve_id);
        abort_if(!$reserve, 404, __('messages.not_found.reserve'));

        $today = $reserve->date;

        $reserves = $user->reserves()->enabled()->unCanceled()->where('date', $today)->dateOrdered()->timeOrdered()->typeOrdered()->get();
        if (isset($reserve)) {
            $today = $reserve->date;
        }
        $other_reserve = ($reserves->count() > 1 ? $reserves->where('id', '!=', $reserve->id)->first() : null);

        $month_calendar = MonthCalendar::yearMonthBy($today->year, $today->month)->first();
        abort_if(!$month_calendar, 404, __('not_found.month_calender'));

        $calendars = Calendar::periodBy($month_calendar->start_date, $month_calendar->end_date)->orderBy('date')->get();

        $previous_date = $today->copy()->subMonth();
        $next_date = $today->copy()->addMonth();

        $day_calendar = $calendars->where('date', $today)->first();

        if (empty($reserve)) {
            return view('pages.reserve.change.index')
                ->with('day_calendar', $day_calendar)
                ->with('previous_date', $previous_date)
                ->with('next_date', $next_date)
                ->with('month_calendar', $month_calendar)
                ->with('calendars', $calendars)
            ;
        }
        else if (op($reserve)->type == ReserveTypes::LUNCHBOX) {
            $time_schedules = TimeSchedule::lunchbox()->enabled()->orderBy('time')->get();

            return view('pages.reserve.change.index_lunchbox')
                ->with('reserve', $reserve)
                ->with('other_reserve', $other_reserve)
                ->with('day_calendar', $day_calendar)
                ->with('previous_date', $previous_date)
                ->with('next_date', $next_date)
                ->with('month_calendar', $month_calendar)
                ->with('calendars', $calendars)
                ->with('time_schedules', $time_schedules)
            ;
        }
        else {
            $time_schedules = ($user->affiliation_detail->is_soccer==Flags::ON ? TimeSchedule::soccer() : TimeSchedule::noSoccer())->enabled()->orderBy('time')->get();
            $start_time = $time_schedules->min('time');
            $end_time = $time_schedules->max('time');

            $empty_states = EmptyState::dateBy($today)->timeRangeBy($start_time, $end_time)
                ->selectRaw('time, FLOOR(SUM(empty_seat_count) * 100 / SUM(seat_count)) as empty_seat_rate')
                ->groupBy('time')
                ->orderBy('time')
                ->get();

            return view('pages.reserve.change.index_visit')
                ->with('reserve', $reserve)
                ->with('other_reserve', $other_reserve)
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

    public function post_visit(Request $request, $reserve_id) {
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

        // バリデーション
        $rules = [
            'new_time' => [ 'required', Rule::exists('time_schedules', 'time')->where('type', $reserve->type)->where('is_delete', Flags::OFF) ],
        ];
        $messages = [
            'new_time.required' => '変更先の時刻は必須です。',
            'new_time.exists' => '正しい時刻を選択してください。',
        ];
        $this->try_validate($request->all(), $rules, $messages);

        return $this->trans(function() use($request, $user, $reserve) {
            $new_time = $request->input('new_time');
            $start_mins = $this->time_to_mins($new_time);
            $end_mins = $start_mins + intval(config('system.dining_hall.stay_time_mins', 20)) - 1;
            $start_time = $this->mins_to_time($start_mins);
            $end_time = $this->mins_to_time($end_mins);

            $is_empty_seat_short = EmptyState::dateBy($reserve->date)->timeRangeBy($start_time, $end_time)->where('empty_seat_count', '<', $reserve->reserve_count)->exists();
            if ($is_empty_seat_short) {
                return redirect()->action([ self::class, 'index' ], [ 'date'=>$reserve->date->format('Y-m-d') ])
                    ->withInput()
                    ->with('error', __('messages.error.reserve_seat_short'));
            }

            $old_start_time = $reserve->time;
            $old_end_time = $reserve->end_time;

            $reserve->time = $start_time;
            $reserve->end_time = $end_time;
            $reserve->remind_dt = null;
            $this->save($reserve, $user);

            // 変更前の予約時間の空き情報を更新する
            EmptyState::rebuild($reserve->date, $old_start_time, $old_end_time);

            // 変更後の予約時間の空き情報を更新する
            $reserve->rebuild_empty_states();

            // LINE通知
            $canceled_view = 'templates.line.visit_changed';
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

    public function post_lunchbox(Request $request, $reserve_id) {
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
            'new_time' => [ 'required', Rule::exists('time_schedules', 'time')->where('type', ReserveTypes::LUNCHBOX)->where('is_delete', Flags::OFF) ],
        ];
        $messages = [
            'new_date.required' => '変更先の日付は必須です。',
            'new_date.date' => '正しい日付を入力してください。',
            'new_date.after_or_equal' => '変更先の日付は２日後以降にしてください。',
            'new_time.required' => '変更先の受取時間を選択してください。',
            'new_time.exists' => '変更先の受取時間が正しくありません。',
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
        $reserve_types = [ ReserveTypes::LUNCHBOX ];
        $is_reserve_exists = Reserve::dateBy($new_date)->userBy($user)->typesBy($reserve_types)->enabled()->unCanceled()->exists();
        if ($is_reserve_exists) {
            return redirect()->action([ self::class, 'index' ], [ 'date'=>$reserve->date->format('Y-m-d') ])
                ->withInput()
                ->with('error', __('messages.error.reserve_exists'));
        }

        // 料理メニューの設定有無のチェック
        $dish_types = [ DishTypes::LUNCHBOX, DishTypes::BOUT_LUNCHBOX ];
        $is_dish_menu_exists = $calendar->dish_menus()->dishTypesBy($dish_types)->exists();
        if (!$is_dish_menu_exists) {
            return redirect()->action([ self::class, 'index' ], [ 'date'=>$reserve->date->format('Y-m-d') ])
                ->withInput()
                ->with('error', __('messages.error.no_dish_menu'));
        }

        return $this->trans(function() use($request, $user, $reserve, $calendar) {
            $reserve->date = $calendar->date;
            $new_time = $request->input('new_time');
            if ($reserve->time != $new_time) {
                $reserve->time = $new_time;
            }
            $reserve->remind_dt = null;
            $this->save($reserve, $user);

            // LINE通知
            $canceled_view = 'templates.line.lunchbox_changed';
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
