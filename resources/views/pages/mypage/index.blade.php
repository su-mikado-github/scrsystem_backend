@extends('layouts.default')

@use(Carbon\Carbon)
@use(App\Genders)
@use(App\AffiliationDetailTypes)

<x-script>
import { SCRSPage } from "/scrs-pages.js";

class MypagePage extends SCRSPage {

    #affiliation = null;
    #affiliationDetailType = null;
    #schoolYearFieldset = null;
    #type1AffiliationDetailFieldset = null;
    #type2AffiliationDetailFieldset = null;

    #affiliationChanged(target) {
        const affiliationId = target.value;
        const selectedOption = target.options[target.selectedIndex] || null;
        const detailType = selectedOption.dataset.detail_type || 0;
        this.#affiliationDetailType.value = detailType;
        this.#schoolYearFieldset.css(detailType != AffiliationDetailTypes.INTERNAL.id, "d-none");
        this.#type1AffiliationDetailFieldset.css(detailType != AffiliationDetailTypes.INTERNAL.id, "d-none");
        this.#type2AffiliationDetailFieldset.css(detailType != AffiliationDetailTypes.EXTERNAL.id, "d-none");
    }

    constructor() {
        super();
        //
        this.#affiliation = this.field("affiliation")?.handle("change");
        this.#affiliationDetailType = this.field("affiliation_detail_type");
        this.#schoolYearFieldset = this.field("school_year_fieldset");
        this.#type1AffiliationDetailFieldset = this.field("type1_affiliation_detail_fieldset");
        this.#type2AffiliationDetailFieldset = this.field("type2_affiliation_detail_fieldset");

//        this.#affiliationChanged(this.#affiliation);
    }

    affiliation_change(e) {
        //
        this.#affiliationChanged(e.target);
    }

}

SCRSPage.startup(()=>new MypagePage());
</x-script>

@section('page.title')
マイページ（編集）
@endsection

@section('main')
<p class="mb-0">※更新する場合には、<b>最下部の「保存する」ボタン</b>をタップしてください。</p>
<div class="rounded-3 scrs-bg-sheet px-2 py-3">
    <h6>氏名（漢字）<span class="text-danger">*</span></h6>
    <div class="row g-2 mb-3">
        <div class="col">
            <input type="text" name="last_name" class="form-control py-2 @error('last_name') is-invalid @enderror" placeholder="姓" value="{{ old('last_name', $user->last_name) }}">
            @error('last_name')<p class="text-danger">{{ $message }}</p>@enderror
        </div>
        <div class="col">
            <input type="text" name="first_name" class="form-control py-2 @error('first_name') is-invalid @enderror" placeholder="名" value="{{ old('first_name', $user->first_name) }}">
            @error('first_name')<p class="text-danger">{{ $message }}</p>@enderror
        </div>
    </div>

    <h6>氏名（ふりがな）<span class="text-danger">*</span></h6>
    <div class="row g-2 mb-3">
        <div class="col">
            <input type="text" name="last_name_kana" class="form-control py-2 @error('last_name_kana') is-invalid @enderror" placeholder="せい" value="{{ old('last_name_kana', $user->last_name_kana) }}">
            @error('last_name_kana')<p class="text-danger">{{ $message }}</p>@enderror
        </div>
        <div class="col">
            <input type="text" name="first_name_kana" class="form-control py-2 @error('first_name_kana') is-invalid @enderror" placeholder="めい" value="{{ old('first_name_kana', $user->first_name_kana) }}">
            @error('first_name_kana')<p class="text-danger">{{ $message }}</p>@enderror
        </div>
    </div>

    <h6>性別<span class="text-danger">*</span></h6>
    <div class="row g-2 mb-3">
        <div class="col">
            <div class="form-check form-check-inline">
                <input class="form-check-input scrs-radio-color" type="radio" name="sex" value="{!! Genders::MALE !!}" {{ old('sex', $user->sex)==Genders::MALE ? 'checked' : '' }}>
                <label class="form-check-label">男性</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input scrs-radio-color" type="radio" name="sex" value="{!! Genders::FEMALE !!}" {{ old('sex', $user->sex)==Genders::FEMALE ? 'checked' : '' }}>
                <label class="form-check-label">女性</label>
            </div>
            @error('sex')<p class="text-danger">{{ $message }}</p>@enderror
        </div>
    </div>

    <h6>生年月日<span class="text-danger">*</span></h6>
    <div class="row g-2 mb-3">
        <div class="col">
            <select name="birthday_year" class="form-select py-2 @error('birthday_year') is-invalid @enderror @error('birthday') is-invalid @enderror">
                @foreach($past_years as $item)
                <option value="{!! $item->value !!}" {{ old('birthday_year', Carbon::parse($user->birthday ?? today())->year)==$item->value ? 'selected' : '' }}>{{ $item->text }}</option>
                @endforeach
            </select>
            @error('birthday_year')<p class="text-danger">{{ $message }}</p>@enderror
        </div>
        <div class="col">
            <select name="birthday_month" class="form-select py-2 @error('birthday_month') is-invalid @enderror @error('birthday') is-invalid @enderror">
                @foreach($months as $item)
                <option value="{!! $item->value !!}" {{ old('birthday_month', Carbon::parse($user->birthday ?? today())->month)==$item->value ? 'selected' : '' }}>{{ $item->text }}</option>
                @endforeach
            </select>
            @error('birthday_month')<p class="text-danger">{{ $message }}</p>@enderror
        </div>
        <div class="col">
            <select name="birthday_day" class="form-select py-2 @error('birthday_day') is-invalid @enderror @error('birthday') is-invalid @enderror">
                @foreach($days as $item)
                <option value="{!! $item->value !!}" {{ old('birthday_day', Carbon::parse($user->birthday ?? today())->day)==$item->value ? 'selected' : '' }}>{{ $item->text }}</option>
                @endforeach
            </select>
            @error('birthday_day')<p class="text-danger">{{ $message }}</p>@enderror
        </div>
        @error('birthday')<p class="col-12 text-danger">{{ $message }}</p>@enderror
    </div>

    <h6>所属<span class="text-danger">*</span></h6>
    <div class="row g-2 mb-3">
        <div class="col">
            <input type="hidden" data-field="affiliation_detail_type" name="affiliation_detail_type" value="{!! old('affiliation_detail_type', $user->affiliation->detail_type ?? '0') !!}">
            <select name="affiliation" data-field="affiliation" class="form-select py-2 @error('affiliation') is-invalid @enderror">
                <option value="0" data-detail_type="0" {{ old('affiliation', $user->affiliation_id ?? 0)==0 ? 'selected' : '' }}>選択してください</option>
                @foreach($affiliations as $item)
                <option value="{!! $item->id !!}" {{ old('affiliation', $user->affiliation_id)==$item->id ? 'selected' : '' }} data-detail_type="{!! $item->detail_type !!}">{{ $item->name }}</option>
                @endforeach
            </select>
            @error('affiliation')<p class="text-danger">{{ $message }}</p>@enderror
        </div>
    </div>

    @eval($is_affiliation_detail_internal = (old('affiliation_detail_type', optional($user->affiliation)->detail_type) == AffiliationDetailTypes::INTERNAL) ?? false)
    @eval($is_affiliation_detail_external = (old('affiliation_detail_type', optional($user->affiliation)->detail_type) == AffiliationDetailTypes::EXTERNAL) ?? false)
    <div data-field="school_year_fieldset" class="{!! $is_affiliation_detail_internal ? '' : 'd-none' !!}">
        <h6>学年<span class="text-danger">*</span></h6>
        <div class="row g-2 mb-3">
            <div class="col">
                <select name="school_year" class="form-select py-2 @error('school_year') is-invalid @enderror">
                    <option value="0" {{ old('school_year', $user->school_year_id ?? 0)==0 ? 'selected' : '' }}>選択してください</option>
                    @foreach($school_years as $item)
                    <option value="{!! $item->id !!}" {{ old('school_year', $user->school_year_id)==$item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                    @endforeach
                </select>
                @error('school_year')<p class="text-danger">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    @eval($type1_affiliation_details = $affiliation_details->where('detail_type', AffiliationDetailTypes::INTERNAL))
    <div data-field="type1_affiliation_detail_fieldset" class="{!! $is_affiliation_detail_internal ? '' : 'd-none' !!}">
        <h6>所属詳細（学部）<span class="text-danger">*</span></h6>
        <p class="mb-0">所属で<span class="text-danger">”その他”以外</span>を選択された方は、こちらをご選択ください。</p>
        <div class="row g-2 mb-3">
            <div class="col">
                <select name="type1_affiliation_detail" class="form-select py-2 @error('type1_affiliation_detail') is-invalid @enderror">
                    <option value="0" {{ old('type1_affiliation_detail', $user->affiliation_detail_id ?? 0)==0 ? 'selected' : '' }}>選択してください</option>
                    @foreach($type1_affiliation_details as $item)
                    <option value="{!! $item->id !!}" {{ old('type1_affiliation_detail', $user->affiliation_detail_id)==$item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                    @endforeach
                </select>
                @error('type1_affiliation_detail')<p class="text-danger">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    @eval($type2_affiliation_details = $affiliation_details->where('detail_type', AffiliationDetailTypes::EXTERNAL))
    <div data-field="type2_affiliation_detail_fieldset" class="{!! $is_affiliation_detail_external ? '' : 'd-none' !!}">
        <h6>所属詳細（その他）<span class="text-danger">*</span></h6>
        <p class="mb-0">所属で<span class="text-danger">”その他”</span>を選択された方は、こちらをご選択ください。</p>
        <div class="row g-2 mb-3">
            <div class="col">
                <select name="type2_affiliation_detail" class="form-select py-2 @error('type2_affiliation_detail') is-invalid @enderror">
                    <option value="0" {{ old('type2_affiliation_detail', $user->affiliation_detail_id ?? 0)==0 ? 'selected' : '' }}>選択してください</option>
                    @foreach($type2_affiliation_details as $item)
                    <option value="{!! $item->id !!}" {{ old('type2_affiliation_detail', $user->affiliation_detail_id)==$item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                    @endforeach
                </select>
                @error('type2_affiliation_detail')<p class="text-danger">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    <h6>電話番号<span class="text-danger">*</span></h6>
    <div class="row g-2">
        <div class="col-6">
            <input type="tel" name="telephone_no" class="form-control py-2 @error('telephone_no') is-invalid @enderror" placeholder="00000000000" value="{{ old('telephone_no', $user->telephone_no) }}">
            @error('telephone_no')<p class="text-danger text-nowrap" style="overflow-x:visible;">{{ $message }}</p>@enderror
        </div>
    </div>
    <p class="mb-3">
        <small class="d-block">※半角数字でご入力ください</small>
        <small class="d-block">※ハイフン（-）なしでご入力ください</small>
    </p>

    <h6>メールアドレス<span class="text-danger">*</span></h6>
    <div class="row g-2 mb-3">
        <div class="col">
            <input type="email" name="email" class="form-control py-2 @error('email') is-invalid @enderror" placeholder="abcd@xxx.com" value="{{ old('email', $user->email) }}">
            @error('email')<p class="text-danger">{{ $message }}</p>@enderror
        </div>
    </div>
</div>

<br>

<div class="d-flex justify-content-center">
    <button type="submit" class="btn scrs-bg-main-button col-8">保存する</button>
</div>
@endsection
