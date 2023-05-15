<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Flags;
use App\Genders;
use App\AffiliationDetailTypes;

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
        $user = $this->user();

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
//            ->with('user', $user)
            ->with('past_years', $past_years)
            ->with('months', $months)
            ->with('days', $days)
            ->with('school_years', $school_years)
            ->with('affiliations', $affiliations)
            ->with('affiliation_details', $affiliation_details)
        ;
    }

    public function post(Request $request) {
        $user = $this->user();

        $rules = [
            //
            'last_name' => [ 'required' ],
            'first_name' => [ 'required' ],
            'last_name_kana' => [ 'required', 'hiragana' ],
            'first_name_kana' => [ 'required', 'hiragana' ],
            'sex' => [ 'required', 'in:' . Genders::ids() ],
            'birthday_year' => [ 'required', 'integer' ],
            'birthday_month' => [ 'required', 'integer', 'month' ],
            'birthday_day' => [ 'required', 'integer', function($attribute, $value, $fail) use($request) {
                $birthday_year = $request->input('birthday_year');
                $birthday_month = $request->input('birthday_month');
                if (in_array($birthday_month, [ 1, 3, 5, 7, 8, 10, 12 ])) {
                    if ($value < 1 || 31 < $value) {
                        $fail(__('validation.day', [ 'attribute'=>__('validation.attributes.' . $attribute), 'max_day'=>31 ]));
                    }
                }
                else if (in_array($birthday_month, [ 4, 6, 9, 11 ])) {
                    if ($value < 1 || 30 < $value) {
                        $fail(__('validation.day', [ 'attribute'=>__('validation.attributes.' . $attribute), 'max_day'=>30 ]));
                    }
                }
                else if (in_array($birthday_month, [ 2 ])) {
                    $is_leap_year = (($year % 4 == 0 && $year % 100 != 0) || $year % 400 == 0);
                    if ($is_leap_year && ($value < 1 || 29 < $value)) {
                        $fail(__('validation.day', [ 'attribute'=>__('validation.attributes.' . $attribute), 'max_day'=>29 ]));
                    }
                    else if ($is_leap_year && ($value < 1 || 28 < $value)) {
                        $fail(__('validation.day', [ 'attribute'=>__('validation.attributes.' . $attribute), 'max_day'=>28 ]));
                    }
                }
            } ],
            'affiliation' => [ 'required', 'integer', 'min:1', 'exists:affiliations,id' ],
            'school_year' => [ 'exclude_unless:affiliation_detail_type,' . AffiliationDetailTypes::INTERNAL, 'required', 'integer', 'min:1', Rule::exists('school_years', 'id')->where('is_delete', Flags::OFF) ],
            'type1_affiliation_detail' => [ 'exclude_unless:affiliation_detail_type,' . AffiliationDetailTypes::INTERNAL, 'required', 'integer', 'min:1', Rule::exists('affiliation_details', 'id')->where('is_delete', Flags::OFF)->where('detail_type', AffiliationDetailTypes::INTERNAL) ],
            'type2_affiliation_detail' => [ 'exclude_unless:affiliation_detail_type,' . AffiliationDetailTypes::EXTERNAL, 'required', 'integer', 'min:1', Rule::exists('affiliation_details', 'id')->where('is_delete', Flags::OFF)->where('detail_type', AffiliationDetailTypes::EXTERNAL) ],
            'telephone_no' => [ 'required', 'regex:/^[0-9]+$/i' ],
            'email' => [ 'required', 'email:rfc' ],
        ];
        $messages = [
            'affiliation.min' => __('validation.required_select', [ 'attribute' => __('validation.attributes.affiliation') ]),
            'school_year.min' => __('validation.required_select', [ 'attribute' => __('validation.attributes.school_year') ]),
            'type1_affiliation_detail.min' => __('validation.required_select', [ 'attribute' => __('validation.attributes.type1_affiliation_detail') ]),
            'type2_affiliation_detail.min' => __('validation.required_select', [ 'attribute' => __('validation.attributes.type2_affiliation_detail') ]),
            'telephone_no.regex' => __('validation.telephone_no', [ 'attribute' => __('validation.attributes.telephone_no') ]),
        ];

        $this->try_validate($request, $rules, $messages, function($validator) use($request) {
            if ($request->filled([ 'birthday_year', 'birthday_month', 'birthday_day' ])) {
                $birthday = Carbon::createFromDate($request->input('birthday_year'), $request->input('birthday_month'), $request->input('birthday_day'));
                if ($birthday->gte(today())) {
                    $validator->errors()->add('birthday', __('validation.past_day', [ 'attribute'=>__('validation.attributes.birthday') ]));
                }
            }
        });

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
        if ($affiliation->detail_type == AffiliationDetailTypes::INTERNAL) {
            $user->affiliation_detail_id = $request->input('type1_affiliation_detail');
            $user->school_year_id = $request->input('school_year');
        }
        else {
            $user->affiliation_detail_id = $request->input('type2_affiliation_detail');
            $user->school_year_id = null;
        }
        $user->telephone_no = $request->input('telephone_no');
        $user->email = $request->input('email');
        $user->last_login_dt = now();
        $user->is_initial_setting = Flags::ON;
        $this->save($user, $user);

        return redirect()->action([ self::class, 'index' ]);
    }
}
