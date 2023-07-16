<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Ticket;
use App\Models\BuyTicket;

class BuyTicketController extends Controller {
    //
    public function index() {
        $user = $this->user();

        $tickets = Ticket::enabled()->orderBy('display_order')->get();

        return view('pages.buy_ticket.index')
            ->with('user', $user)
            ->with('tickets', $tickets)
            ->with('last_ticket_count', $user->last_ticket_count)
        ;
    }

    public function post(Request $request, $ticket_id) {
        $user = $this->user();

        $ticket = Ticket::enabled()->find($ticket_id);
        abort_if(!$ticket_id, 404, __('messages.not_found.ticket'));

        return $this->trans(function() use($request, $user, $ticket) {
            $today = today();

            $buy_ticket = new BuyTicket();
            $buy_ticket->user_id = $user->id;
            $buy_ticket->buy_dt = now();
            $buy_ticket->buy_year = $today->year;
            $buy_ticket->buy_month = $today->month;
            $buy_ticket->buy_day = $today->day;
            $buy_ticket->ticket_id = $ticket->id;
            $buy_ticket->ticket_count = $ticket->ticket_count;
            $this->save($buy_ticket, $user);

            //LINEで食券の購入を通知する
            $message = view('templates.line.buy_ticket')->with('user', $user)->with('buy_ticket', $buy_ticket)->render();
            if ($this->line_api()->push_messages($user->line_user->line_owner_id, [ $message ]) === false) {
                DB::rollback();
                return redirect()->action([ self::class, 'index' ], [ 'date'=>$date ])
                    ->withInput()
                    ->with('error', __('messages.error.push_messages'));
            }

            if ($request->has('backward')) {
                return redirect()->to($request->input('backward'))->withInput();
            }

            return view('pages.buy_ticket.post')
                ->with('ticket', $ticket)
            ;
        });
    }
}
