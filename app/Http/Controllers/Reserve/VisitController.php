<?php

namespace App\Http\Controllers\Reserve;

use Carbon\Carbon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

use App\Flags;
use App\ReserveTypes;

use App\Models\Calendar;
use App\Models\TimeSchedule;
use App\Models\EmptyState;
use App\Models\EmptySeat;
use App\Models\Seat;
use App\Models\Reserve;
use App\Models\ReserveSeat;
use App\Models\UseTicket;

class VisitController extends Controller {
    //
    public function index(Request $request, $date=null) {
        $user = $this->user();

//        logger()->debug(session()->all());

        $today = (isset($date) ? Carbon::parse($date) : today());
        $start_date = transform(Calendar::find_last_sunday($date), function($calendar) { return Carbon::parse($calendar->date); });
        abort_if(!$start_date, 400, __('messages.not_found.calendar'));
        $end_date = $start_date->copy()->addDay(6);

        // 回数券の残数確認
        if ($user->last_ticket_count == 0) {
            return redirect()->route('buy_ticket')
                ->with([
                    'warning' => __('messages.warning.ticket_by_short'),
                    'backward' => route('reserve.visit', compact('date')),
                ])
            ;
        }

        $calendars = Calendar::with([ 'reserves' ])->enabled()->range($start_date, $end_date)->orderBy('date')->get();
        $day_calendar = Calendar::dateBy($today)->enabled()->first();

        $time_schedules = ($user->affiliation_detail->is_soccer==Flags::ON ? TimeSchedule::soccer() : TimeSchedule::noSoccer())->orderBy('time')->get();
        $start_time = $time_schedules->min('time');
        $end_time = $time_schedules->max('time');

        $seat_count = Seat::enabled()->count();

        $empty_states = EmptyState::periodBy($start_date, $end_date)->timeRangeBy($start_time, $end_time)->get();

        $reserves = $user->reserves()->enabled()->unCanceled()->noCheckin()->get();
        return view('pages.reserve.visit.index')
            ->with('start_date', $start_date)
            ->with('end_date', $end_date)
            ->with('calendars', $calendars)
            ->with('time_schedules', $time_schedules)
            ->with('empty_states', $empty_states)
            ->with('seat_count', $seat_count)
            ->with('day_calendar', $day_calendar)
            ->with('reserves', $reserves)
        ;
    }

    protected function allocate_seats($empty_seats, $person_count) {
        // 人数を満たす座席グループがあれば、その座席グループ番号を返却する。席数の少ない座席グループから引き当てる
        $allocated_empty_seats = $empty_seats->where('seat_count', '>=', $person_count)->sortBy('seat_count')->slice(0, 1);
        if ($allocated_empty_seats->count() > 0) {
            return $allocated_empty_seats->pluck('seat_group_no');
        }

        // 座席グループで収まらない場合、複数の座席グループを使って座席を割り当てる
        $sorted_empty_seats = $empty_seats->sortByDesc('seat_count');
        $count = $person_count;
        $seat_group_nos = collect();
        foreach ($empty_seats as $empty_seat) {
            if ($count <= $empty_seat->seat_count) {
                $seat_group_nos->push($empty_seat->seat_group_no);
                break;
            }
            else {
                $seat_group_nos->push($empty_seat->seat_group_no);
                $count -= $empty_seat->seat_count;
            }
        }

        return $seat_group_nos;
    }

    public function post(Request $request, $date) {
        $user = $this->user();

        if ($user->affiliation_detail->is_soccer == Flags::ON) {
            $rules = [
                'reserve_time' => [ 'required', Rule::exists('time_schedules', 'time')->where('type', ReserveTypes::VISIT_SOCCER) ],
            ];
        }
        else {
            $rules = [
                'reserve_time' => [ 'required', Rule::exists('time_schedules', 'time')->where('type', ReserveTypes::VISIT_NO_SOCCER) ],
                'person_count' => [ 'required', 'integer', 'min:1', 'max:' . Seat::enabled()->count() ],
                'is_table_share' => [ 'required', 'in:' . Flags::ids() ]
            ];
        }
        $messages = [
            'reserve_time.required' => '予約時間が不明です。',
            'reserve_time.exists' => '予約時間が不正です。',
            'person_count.required' => '予約人数は必須です。',
            'person_count.integer' => '予約人数に数値以外を指定しないでください。',
            'person_count.min' => '予約人数は１名以上にしてください。',
            'person_count.max' => '予約人数が多すぎます。',
            'is_table_share.required' => '相席の指定は必須です。',
            'is_table_share.in' => '相席の指定が不正です。',
        ];
        $this->try_validate($request->all(), $rules, $messages);

        $reserve_time = $request->input('reserve_time');
        if ($user->affiliation_detail->is_soccer == Flags::ON) {
            $person_count = 1;
            $is_table_share = Flags::ON;
        }
        else {
            $person_count = intval($request->input('person_count'));
            $is_table_share = Flags::of($request->input('is_table_share'))->id;
        }

        $start_mins = $this->time_to_mins($reserve_time);
        $end_mins = $start_mins + intval(config('system.dining_hall.stay_time_mins', 20)) - 1;  //TODO:暫定で20分間の利用と考える
        $need_seat_count = 20 * $person_count;

        $start_time = $this->mins_to_time($start_mins);
        $end_time = $this->mins_to_time($end_mins);

        $empty_seats = EmptySeat::dateBy($date)->timeRangeBy($start_time, $end_time)
            ->selectRaw('time, seat_group_no, COUNT(*) as seat_count')
            ->groupByRaw('time, seat_group_no')
            ->orderBy('time')->orderBy('seat_count')->orderBy('seat_group_no')
            ->get();

        $time_empty_seats = $empty_seats->groupBy([ 'time' ]);

        // 購入回数券を引き当てる
        $valid_tickets = $user->valid_tickets()->validateBy()->orderBy('buy_dt')->get();
        $buy_ticket_ids = collect();
        $valid_ticket = null;
        $valid_ticket_count = 0;
        for ($i=0; $i<$person_count; $i++) {
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
        // logger()->debug(sprintf('%s(%s) => %s', __FILE__, __LINE__, print_r([ 'buy_ticket_ids.count'=>$buy_ticket_ids->count(), 'person_count'=>$person_count ], true)));
        if ($buy_ticket_ids->count() < $person_count) {
            return redirect()->route('buy_ticket')
                ->withInput()
                ->with([
                    'warning' => __('messages.warning.ticket_by_short'),
                    'backward' => route('reserve.visit', compact('date')),
                ])
            ;
        }

        return $this->trans(function() use($user, $date, $start_mins, $end_mins, $person_count, $is_table_share, $start_time, $end_time, $time_empty_seats, $buy_ticket_ids) {
            $reserved_seat_ids = null;
            for ($mins=$start_mins; $mins<=$end_mins; $mins++) {
                $time = sprintf('%02d:%02d:00', floor($mins / 60), ($mins % 60));

                $seat_group_nos = $this->allocate_seats($time_empty_seats[$time], $person_count);
                if ($seat_group_nos->count() == 0) {
                    DB::rollBack();
                    return redirect()->action([ self::class, 'index' ], [ 'date'=>$date ])
                        ->with('error', __('messages.error.reserve_seat_short'));    //TODO:メッセージ暫定
                }

                $seats = Seat::whereIn('seat_group_no', $seat_group_nos)->get();
                if ($seats->count() < $person_count) {
                    DB::rollBack();
                    return redirect()->action([ self::class, 'index' ], [ 'date'=>$date ])
                        ->with('error', __('messages.error.reserve_seat_short'));    //TODO:メッセージ暫定
                }

                if (!$reserved_seat_ids) {
                    $reserved_seat_ids = $seats->pluck('id');
                }
                else {
                    $temp_seat_ids = $reserved_seat_ids->intersect($seats->pluck('id'));
                    if ($temp_seat_ids->count() < $person_count) {
                        DB::rollBack();
                        return redirect()->action([ self::class, 'index' ], [ 'date'=>$date ])
                            ->with('error', __('messages.error.reserve_seat_short'));    //TODO:メッセージ暫定
                    }
                    $reserved_seat_ids = $temp_seat_ids;
                }
            }

            $is_soccer = $user->affiliation_detail->is_soccer;

            $time_schedule_times = ($is_soccer ? TimeSchedule::soccer() : TimeSchedule::noSoccer())->timePeriodBy($start_time, $end_time)->get()->pluck('time');

            // 予約情報の保存
            $reserve = new Reserve();
            $reserve->type = ($is_soccer ? ReserveTypes::VISIT_SOCCER : ReserveTypes::VISIT_NO_SOCCER);
            $reserve->date = $date;
            $reserve->time = $start_time;
            $reserve->end_time = $end_time;
            $reserve->user_id = $user->id;
            $reserve->reserve_dt = now();
            $reserve->reserve_count = $person_count;
            $reserve->is_table_share = $is_table_share;
            $this->save($reserve, $user);

            // 予約座席
            foreach ($reserved_seat_ids as $reserve_seat_id) {
                $reserve_seat = new ReserveSeat();
                $reserve_seat->reserve_id = $reserve->id;
                $reserve_seat->seat_id = $reserve_seat_id;
                $this->save($reserve_seat, $user);
            }

            // 回数券
            foreach ($buy_ticket_ids as $buy_ticket_id) {
                $use_ticket = new UseTicket();
                $use_ticket->reserve_id = $reserve->id;
                $use_ticket->user_id = $user->id;
                $use_ticket->buy_ticket_id = $buy_ticket_id;
                $use_ticket->use_dt = now();
                $this->save($use_ticket, $user);
            }

            // 空き状況を更新
            $reserve->rebuild_empty_states();

            $reserve = $reserve->fresh();

            //　LINE通知
            $message = view('templates.line.visit_reserved')->with('user', $user)->with('reserve', $reserve)->render();
            if (!$this->line_api()->push_messages($user->line_user->line_owner_id, [ $message ])) {
                DB::rollBack();
                return redirect()->action([ self::class, 'index' ], [ 'date'=>$date ])
                    ->with('error', __('messages.error.push_messages'));
            }

            return view('pages.reserve.visit.post')
                ->with('reserve', $reserve)
            ;
        });
    }
}
