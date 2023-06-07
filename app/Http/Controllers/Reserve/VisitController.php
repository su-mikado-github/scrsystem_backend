<?php

namespace App\Http\Controllers\Reserve;

use Carbon\Carbon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Flags;
use App\ReserveTypes;

use App\Models\Calendar;
use App\Models\TimeSchedule;
use App\Models\EmptyState;
use App\Models\EmptySeat;
use App\Models\Seat;
use App\Models\Reserve;
use App\Models\ReserveSeat;

class VisitController extends Controller {
    //
    public function index(Request $request, $date=null) {
        $user = $this->user();

        $today = (isset($date) ? Carbon::parse($date) : today());
        $start_date = transform(Calendar::find_last_sunday($date), function($calendar) { return Carbon::parse($calendar->date); });
        abort_if(!$start_date, 400, __('messages.not_found.calendar'));
        $end_date = $start_date->copy()->addDay(6);

        $calendars = Calendar::with([ 'reserves' ])->range($start_date, $end_date)->orderBy('date')->get();

        $time_schedules = ($user->affiliation_detail->is_soccer==Flags::ON ? TimeSchedule::soccer() : TimeSchedule::noSoccer())->orderBy('time')->get();
        $start_time = $time_schedules->min('time');
        $end_time = $time_schedules->max('time');

        $seat_count = Seat::enabled()->count();

        $empty_states = EmptyState::periodBy($start_date, $end_date)->timeRangeBy($start_time, $end_time)->get();
        return view('pages.reserve.visit.index')
            ->with('start_date', $start_date)
            ->with('end_date', $end_date)
            ->with('calendars', $calendars)
            ->with('time_schedules', $time_schedules)
            ->with('empty_states', $empty_states)
            ->with('seat_count', $seat_count)
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

        logger()->debug($request->input());

        $reserve_time = $request->input('reserve_time');
        $person_count = intval($request->input('person_count'));
        $is_table_share = Flags::of($request->input('is_table_share'))->id;

        list($hour, $minute, $second) = explode(':', $reserve_time);
        $start_mins = $hour * 60 + $minute;
        $end_mins = $start_mins + 20 - 1;  //TODO:暫定で20分間の利用と考える
        $need_seat_count = 20 * $person_count;

        $start_time = sprintf('%02d:%02d:00', floor($start_mins / 60), ($start_mins % 60));
        $end_time = sprintf('%02d:%02d:00', floor($end_mins / 60), ($end_mins % 60));

        $empty_seats = EmptySeat::dateBy($date)->timeRangeBy($start_time, $end_time)
            ->selectRaw('time, seat_group_no, COUNT(*) as seat_count')
            ->groupByRaw('time, seat_group_no')
            ->orderBy('time')->orderBy('seat_count')->orderBy('seat_group_no')
            ->get();

        $time_empty_seats = $empty_seats->groupBy([ 'time' ]);

        return $this->trans(function() use($user, $date, $start_mins, $end_mins, $person_count, $is_table_share, $start_time, $end_time, $time_empty_seats) {
            $reserved_seat_ids = null;
            for ($mins=$start_mins; $mins<=$end_mins; $mins++) {
                $time = sprintf('%02d:%02d:00', floor($mins / 60), ($mins % 60));

                $seat_group_nos = $this->allocate_seats($time_empty_seats[$time], $person_count);
                if ($seat_group_nos->count() == 0) {
                    return redirect()->action([ self::class, 'index' ], [ 'date'=>$date ])
                        ->with('error', __('messages.error.reserve_seat_short'));    //TODO:メッセージ暫定
                }

                $seats = Seat::whereIn('seat_group_no', $seat_group_nos)->get();
                if ($seats->count() < $person_count) {
                    return redirect()->action([ self::class, 'index' ], [ 'date'=>$date ])
                        ->with('error', __('messages.error.reserve_seat_short'));    //TODO:メッセージ暫定
                }

                if (!$reserved_seat_ids) {
                    $reserved_seat_ids = $seats->pluck('id');
                }
                else {
                    $temp_seat_ids = $reserved_seat_ids->intersect($seats->pluck('id'));
                    if ($temp_seat_ids->count() < $person_count) {
                        return redirect()->action([ self::class, 'index' ], [ 'date'=>$date ])
                            ->with('error', __('messages.error.reserve_seat_short'));    //TODO:メッセージ暫定
                    }
                    $reserved_seat_ids = $temp_seat_ids;
                }
            }

            $is_soccer = $user->affiliation_detail->is_soccer;

            $time_schedule_times = ($is_soccer ? TimeSchedule::soccer() : TimeSchedule::noSoccer())->timePeriodBy($start_time, $end_time)->get()->pluck('time');
            $start_time_schedule_time = $time_schedule_times->min();
            $end_time_schedule_time = $time_schedule_times->max();

            // 予約情報の保存
            $reserve = new Reserve();
            $reserve->type = ($is_soccer ? ReserveTypes::VISIT_SOCCER : ReserveTypes::VISIT_NO_SOCCER);
            $reserve->date = $date;
            $reserve->time = $start_time_schedule_time;
            $reserve->end_time = $end_time_schedule_time;
            $reserve->user_id = $user->id;
            $reserve->reserve_dt = now();
            $reserve->reserve_count = $person_count;
            $reserve->is_table_share = $is_table_share;
            $this->save($reserve, $user);

            foreach ($reserved_seat_ids as $reserve_seat_id) {
                $reserve_seat = new ReserveSeat();
                $reserve_seat->reserve_id = $reserve->id;
                $reserve_seat->seat_id = $reserve_seat_id;
                $this->save($reserve_seat, $user);
            }

            $reserve->rebuild_empty_states();

            return view('pages.reserve.visit.post')
                ->with('reserve', $reserve->fresh())
            ;
        });
    }
}
