<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

        return $this->trans(function() use($user, $ticket) {
            $buy_ticket = new BuyTicket();
            $buy_ticket->user_id = $user->id;
            $buy_ticket->buy_dt = now();
            $buy_ticket->ticket_id = $ticket->id;
            $buy_ticket->ticket_count = $ticket->ticket_count;
            $this->save($buy_ticket, $user);

            return view('pages.buy_ticket.post')
                ->with('ticket', $ticket)
            ;
        });
    }
}
