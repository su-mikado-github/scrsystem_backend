<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\MypageRequest;

use App\Models\Affiliation;
use App\Models\AffiliationDetail;
use App\Models\SchoolYear;

class MypageController extends Controller {
    protected function build_days($days) {
        return collect(range(1, $days, 1))->map(function($value) {
            return [ 'value'=>$value, 'text'=>sprintf('%d日', $value) ];
        });
    }

    //
    public function index() {
        $user = Auth::user();

        $past_years = collect(range(today()->year, 1900, -1))->map(function($value) {
            return (object)[ 'value'=>$value, 'text'=>sprintf('%d年', $value) ];
        });
        $months = collect(range(1, 12, 1))->map(function($value) {
            return (object)[ 'value'=>$value, 'text'=>sprintf('%d月', $value) ];
        });
        $days = collect(range(1, 31, 1))->map(function($value) {
            return (object)[ 'value'=>$value, 'text'=>sprintf('%d日', $value) ];
        });

        $school_years = SchoolYear::enabled()->orderBy('display_order')->get();

        $affiliations = Affiliation::orderBy('display_order')->get();
        $affiliation_details = AffiliationDetail::orderBy('display_order')->get();

        return view('pages.mypage.index')
            ->with('user', $user)
            ->with('past_years', $past_years)
            ->with('months', $months)
            ->with('days', $days)
            ->with('school_years', $school_years)
            ->with('affiliations', $affiliations)
            ->with('affiliation_details', $affiliation_details)
        ;
    }

    public function put(MypageRequest $request) {
        $user = Auth::user();

        $user->last_name = $request->input('last_name');
        $user->first_name = $request->input('first_name');
        $user->last_name_kana = $request->input('last_name_kana');
        $user->first_name_kana = $request->input('first_name_kana');
        $user->sex = $request->input('sex');
        $birthday_year = $request->input('birthday_year');
        $birthday_month = $request->input('birthday_month');
        $birthday_day = $request->input('birthday_day');
        $user->birthday = Carbon::createFromDate($birthday_year, $birthday_month, $birthday_day);

        $affiliation_id = $request->input('affiliation');
        $affiliation = Affiliation::find($affiliation_id);
        $user->affiliation_id = $affiliation_id;
        if ($affiliation_id->detail_type == 1) {
            $user->affiliation_detail_id = $request->input('type1_affiliation_detail');
        }
        else {
            $user->affiliation_detail_id = $request->input('type2_affiliation_detail');
        }
        $user->last_name = $request->input('last_name');
        $user->last_name = $request->input('last_name');
        $user->last_name = $request->input('last_name');


        return redirect()->action([ self::class, 'index' ]);
    }
}
