<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Flags;
use App\SortTypes;

use App\Http\Controllers\Admin\StatusController;

use App\Models\Calendar;
use App\Models\User;

class StatusDailyController extends Controller {
    //
    public function index(Request $request, $date=null) {
        $full_name_sort = $request->input('full_name_sort', SortTypes::ASC);
        $affiliation_sort = $request->input('affiliation_sort', SortTypes::ASC);
        $affiliation_detail_sort = $request->input('affiliation_detail_sort', SortTypes::ASC);
        $school_year_sort = $request->input('school_year_sort', SortTypes::ASC);
        $sort_orders = collect(explode(',', $request->input('sort_orders', 'full_name,affiliation,affiliation_detail,school_year')));

        //
        $calendar = Calendar::dateBy($date ?? today())->first();
        if (!$calendar) {
            return redirect()->action([ StatusController::class, 'index' ])->with('error', __('messages.not_found.calendar'));
        }

        $users_query = User::with([ 'affiliation', 'affiliation_detail', 'school_year' ])
            ->where('users.is_delete', Flags::OFF)
            ->where('users.is_admin', Flags::OFF)
            ->orderBy('users.is_initial_setting', 'desc')
        ;
        $users_query = $sort_orders
            ->reduce(function($query, $sort_order) use($full_name_sort, $affiliation_sort, $affiliation_detail_sort, $school_year_sort) {
                if ($sort_order == 'full_name') {
                    return $query->fullNameOrder($full_name_sort);
                }
                else if ($sort_order == 'affiliation') {
                    return $query->AffiliationOrder($affiliation_sort);
                }
                else if ($sort_order == 'affiliation_detail') {
                    return $query->AffiliationDetailOrder($affiliation_detail_sort);
                }
                else if ($sort_order == 'school_year') {
                    return $query->SchoolYearOrder($school_year_sort);
                }
                return $query;
            }, $users_query);

        $users = $users_query->selectRaw('users.*, affiliations.display_order as affiliation_display_oder, affiliation_details.display_order as affiliation_detail_display_order, school_years.display_order as school_year_display_order')
            ->distinct()
            ->get();

        return view('pages.admin.status.daily.index')
            ->with('date', $date)
            ->with('calendar', $calendar)
            ->with('users', $users)
            ->with('full_name_sort', $full_name_sort)
            ->with('affiliation_sort', $affiliation_sort)
            ->with('affiliation_detail_sort', $affiliation_detail_sort)
            ->with('school_year_sort', $school_year_sort)
            ->with('sort_orders', $sort_orders)
        ;
    }
}
