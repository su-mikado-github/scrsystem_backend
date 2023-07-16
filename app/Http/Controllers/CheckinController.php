<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use Illuminate\Http\Request;

use App\ReserveTypes;

use App\Models\Reserve;

class CheckinController extends Controller {
    //
    public function index(Request $request, $date=null) {
        $user = $this->user();

        $today = (isset($date) ? Carbon::parse($date) : today());
        $reserve = $user->reserves()->enabled()->dateBy($today)->unCanceled()->first();

        return view('pages.checkin.index')
            ->with('reserve', $reserve)
        ;
    }

    public function post(Request $request, $reserve_id) {
        $user = $this->user();

        $reserve = Reserve::enabled()->find($reserve_id);
        abort_if(!$reserve, 404, __('messages.not_found.reserve'));

        if ($reserve->user_id != $user->id) {
            return redirect()->action([ self::class, 'index' ], [ 'date'=>$reserve->date->format('Y-m-d') ])
                ->with('error', __('messages.error.illegal_user'));
        }

        // 購入回数券を引き当てる
        $reserve_count = $reserve->reserve_count;
        $valid_tickets = $user->valid_tickets()->validateBy()->orderBy('buy_dt')->get();
        $buy_ticket_ids = collect();
        $valid_ticket = null;
        $valid_ticket_count = 0;
        for ($i=0; $i<$reserve_count; $i++) {
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
        if ($buy_ticket_ids->count() < $reserve_count) {
            $reserve_route = ($reserve->type == ReserveTypes::LUNCHBOX ? 'reserve.lunchbox' : 'reserve.visit');
            return redirect()->route('buy_ticket')
                ->withInput()
                ->with([
                    'warning' => __('messages.warning.ticket_by_short'),
                    'backward' => route($reserve_route, compact('date')),
                ])
            ;
        }

        return $this->trans(function() use($request, $user, $reserve) {
            $reserve->checkin_dt = now();
            $this->save($reserve, $user);

            $reserve = $reserve->fresh();

            //　LINE通知
            $view = ($reserve->type == ReserveTypes::LUNCHBOX ? 'templates.line.lunchbox_checkin' : 'templates.line.visit_checkin');
            $message = view($view)->with('user', $user)->with('reserve', $reserve)->render();
            if (!$this->line_api()->push_messages($user->line_user->line_owner_id, [ $message ])) {
                DB::rollBack();
                return redirect()->action([ self::class, 'index' ], [ 'date'=>$date ])
                    ->with('error', __('messages.error.push_messages'));
            }

            return view('pages.checkin.post')
                ->with('reserve', $reserve)
            ;
        });
    }
}
