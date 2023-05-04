<?php

namespace App\Http\Requests;

use Carbon\Carbon;

use Illuminate\Foundation\Http\FormRequest;

use App\Genders;

class MypageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'last_name' => [ 'required' ],
            'first_name' => [ 'required' ],
            'last_name_kana' => [ 'required', 'hiragana' ],
            'first_name_kana' => [ 'required', 'hiragana' ],
            'sex' => [ 'required', 'in:' . Genders::ids() ],
            'birthday_year' => [ 'required', 'integer' ],
            'birthday_month' => [ 'required', 'integer', 'month' ],
            'birthday_day' => [ 'required', 'integer', function($attribute, $value, $fail) {
                $birthday_year = $this->input('birthday_year');
                $birthday_month = $this->input('birthday_month');
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
            'school_year' => [  ],
            'affiliation' => [ 'required', 'integer', 'gte:0', function($attribute, $value, $fail) {
                if ($value != 0) {
                    if (!Affiliation::where('id', $value)->where('is_delete', false)->exists()) {
                        $fail(__('validation.exists', [ 'attribute'=>__('validation.attributes.' . $attribute) ]));
                    }
                }
            } ],
            'type1_affiliation_detail' => [ 'required_unless:affiliation,0', 'integer', 'gte:0', function($attribute, $value, $fail) {

            } ],
            '' => [],
            '' => [],
        ];
    }

    public function withValidator($validator) {
        $validator->after(function($validator) {
            if ($this->filled([ 'birthday_year', 'birthday_month', 'birthday_day' ])) {
                $birthday = Carbon::createFromDate($this->input('birthday_year'), $this->input('birthday_month'), $this->input('birthday_day'));
                if ($birthday->gte(today())) {
                    $validator->errors()->add('birthday', __('validation.past_day', [ 'attribute'=>__('validation.attributes.birthday') ]));
                }
            }
        });
    }
}
