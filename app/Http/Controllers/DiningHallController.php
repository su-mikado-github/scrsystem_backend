<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use Illuminate\Http\Request;

use App\ReserveTypes;

use App\Models\BuyTicket;
use App\Models\Reserve;

class DiningHallController extends Controller {
    //
    public function index(Request $request, $date=null) {
        //
        $today = (isset($date) ? Carbon::parse($date) : today());

        $dining_hall_reserves = Reserve::with([ 'user' ])->enabled()->unCanceled()->dateBy($today)->typesBy([ ReserveTypes::VISIT_SOCCER, ReserveTypes::VISIT_NO_SOCCER ])->orderBy('time')->orderBy('id')->get();
        $lunchbox_reserves = Reserve::with([ 'user' ])->enabled()->unCanceled()->dateBy($today)->typesBy([ ReserveTypes::LUNCHBOX ])->orderBy('id')->get();

        $buy_tickets = BuyTicket::enabled()->unpaid()->orderBy('buy_dt')->get();

        return view('pages.dining_hall.index')
            ->with('today', $today)
            ->with('buy_tickets', $buy_tickets)
            ->with('dining_hall_reserves', $dining_hall_reserves)
            ->with('lunchbox_reserves', $lunchbox_reserves)
        ;
    }

    public function patch_payment(Request $request, $date, $buy_ticket_id) {
        $user = $this->user();

        $buy_ticket = BuyTicket::enabled()->find($buy_ticket_id);
        abort_if(!$buy_ticket, 404, __('messages.not_found.buy_ticket'));

        return $this->trans(function() use($request, $user, $date, $buy_ticket) {
            $buy_ticket->payment_dt = now();
            $this->save($buy_ticket, $user);

            //支払い完了の旨をLINEで通知する
            $message = view('templates.line.buy_ticket_payment')->with('user', $user)->with('buy_ticket', $buy_ticket)->with('checkin_url', route('checkin'))->render();
            if ($this->line_api()->push_messages($buy_ticket->user->line_user->line_owner_id, [ $message ]) === false) {
                DB::rollback();
                return redirect()->action([ self::class, 'index' ], [ 'date'=>$date ])
                    ->withInput()
                    ->with('error', __('messages.error.push_messages'));
            }

            return redirect()->action([ self::class, 'index' ], [ 'date'=>$date ]);
        });
    }
}
