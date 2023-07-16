<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\SortTypes;

use App\Models\BuyTicket;
use App\Models\MonthCalendar;

class TicketsController extends Controller {
    //
    public function index(Request $request, $year=null, $month=null) {
        logger()->debug($request->input());
        if (empty($year)) {
            $year = today()->year;
        }
        if (empty($month)) {
            $month = today()->month;
        }

        $month_calendar = MonthCalendar::yearMonthBy($year, $month)->first();
        abort_if(!$month_calendar, 404, __('messages.not_found.month_calendar'));

        $datetime_sort = $request->input('datetime_sort', SortTypes::ASC);
        $full_name_sort = $request->input('full_name_sort', SortTypes::ASC);
        $affiliation_sort = $request->input('affiliation_sort', SortTypes::ASC);
        $affiliation_detail_sort = $request->input('affiliation_detail_sort', SortTypes::ASC);
        $school_year_sort = $request->input('school_year_sort', SortTypes::ASC);
        $sort_orders = collect(explode(',', $request->input('sort_orders', 'datetime,full_name,affiliation,affiliation_detail,school_year')));

        $buy_tickets_query = BuyTicket::with([ 'user', 'user.affiliation', 'user.affiliation_detail', 'user.school_year' ])->yearMonthBy($month_calendar->year, $month_calendar->month)->joinOn();

        $buy_tickets_query = $sort_orders->reduce(function($query, $sort_order) use($datetime_sort, $full_name_sort, $affiliation_sort, $affiliation_detail_sort, $school_year_sort) {
            if ($sort_order == 'datetime') {
                return $query->buyDtOrder($datetime_sort);
            }
            else if ($sort_order == 'full_name') {
                return $query->fullNameOrder($full_name_sort);
            }
            else if ($sort_order == 'affiliation') {
                return $query->affiliationOrder($affiliation_sort);
            }
            else if ($sort_order == 'affiliation_detail') {
                return $query->affiliationDetailOrder($affiliation_detail_sort);
            }
            else if ($sort_order == 'school_year') {
                return $query->schoolYearOrder($school_year_sort);
            }
            return $query;
        }, $buy_tickets_query);

        $buy_tickets = $buy_tickets_query->selectRaw('buy_tickets.*, users.last_name_kana, users.first_name_kana, affiliations.display_order as affiliation_display_oder, affiliation_details.display_order as affiliation_detail_display_order, school_years.display_order as school_year_display_order')->distinct()->get();

        logger()->debug(print_r($buy_tickets, true));

        return view('pages.admin.tickets.index')
            ->with('month_calendar', $month_calendar)
            ->with('buy_tickets', $buy_tickets)
            ->with('datetime_sort', $datetime_sort)
            ->with('full_name_sort', $full_name_sort)
            ->with('affiliation_sort', $affiliation_sort)
            ->with('affiliation_detail_sort', $affiliation_detail_sort)
            ->with('school_year_sort', $school_year_sort)
            ->with('sort_orders', $sort_orders)
        ;
    }

    public function patch_payment(Request $request, $buy_ticket_id) {
        $user = $this->user();

        $buy_ticket = BuyTicket::enabled()->find($buy_ticket_id);
        abort_if(!$buy_ticket, 404, __('messages.not_found.buy_ticket'));

        return $this->trans(function() use($request, $user, $buy_ticket) {
            $buy_ticket->payment_dt = now();
            $this->save($buy_ticket, $user);

            return redirect()->action([ self::class, "index" ], [ 'year'=>$buy_ticket->buy_year, 'month'=>$buy_ticket->buy_month ])
                ->with('success', __('messages.success.buy_ticket'))
            ;
        });
    }
}
