<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Flags;
use App\ReserveTypes;

use App\Models\Calendar;

class AdminController extends Controller {
    //
    public function index(Request $request, $date=null) {
        $calendar = Calendar::dateBy($date ?? today())->first();
        abort_if(!$calendar, 404, __('messages.not_found.calendar'));

        $visit_reserves = $calendar->reserves()->with([ 'user', 'user.affiliation', 'user.affiliation_detail', 'user.school_year' ])
            ->whereIn('type', [ ReserveTypes::VISIT_SOCCER, ReserveTypes::VISIT_NO_SOCCER ])
            ->whereExists(function($sub_query) {
                $sub_query
                    ->selectRaw(1)
                    ->from('users')
                    ->whereRaw('users.id = reserves.user_id')
                    ->where('users.is_delete', Flags::OFF);
            })
            ->get()
        ;

        $lunchbox_reserves = $calendar->reserves()->with([ 'user', 'user.affiliation', 'user.affiliation_detail', 'user.school_year' ])
            ->whereIn('type', [ ReserveTypes::LUNCHBOX ])
            ->whereExists(function($sub_query) {
                $sub_query
                    ->selectRaw(1)
                    ->from('users')
                    ->whereRaw('users.id = reserves.user_id')
                    ->where('users.is_delete', Flags::OFF);
            })
            ->get()
        ;

        return view('pages.admin.index')
            ->with('date', $date)
            ->with('calendar', $calendar)
            ->with('visit_reserves', $visit_reserves)
            ->with('lunchbox_reserves', $lunchbox_reserves)
        ;
    }
}

