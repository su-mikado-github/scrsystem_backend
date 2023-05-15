@extends('layouts.default')

@use(Carbon\Carbon)
@use(App\Flags)

<x-script>
import { SCRSPage } from "/scrs-pages.js";
import { SCRSConfirmDialog } from "/dialogs/confirm-dialog.js";

class ReserveVisitPage extends SCRSPage {
    {{-- #previousWeek = null;
    #nextWeek = null; --}}

    #toggles = null;

    #reservedDialog = null;

    constructor() {
        super();
        //
        {{-- this.#previousWeek = this.action("previousWeek")?.handle("click");
        this.#nextWeek = this.action("nextWeek")?.handle("click"); --}}

        this.#toggles = this.actions("toggle").map((f)=>f.handle("click"));
        this.#reservedDialog = new SCRSConfirmDialog(this, "reserveConfirm", null, [ "show", "hide", "ok" ]);
    }

    reserveConfirm_show(e) {
//        e.preventDefault();
    }

    reserveConfirm_hide(e) {

    }

    reserveConfirm_ok(e) {
        this.#reservedDialog.close();
    }

    toggle_click(e) {
        const toggle = this.proxy(e.target, "toggle");
        toggle.css(!toggle.hasClass("scrs-selected"), "scrs-selected");
    }

    {{-- previousWeek_click(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    nextWeek_click(e) {
        e.preventDefault();
        e.stopPropagation();
    } --}}
}

SCRSPage.startup(()=>new ReserveVisitPage());
</x-script>

<x-style>
td:has(.scrs-selected) {
    background-color: #ff95ff !important;
}
</x-style>

@section('page.title')
予約受付
@endsection

@section('main')
@if((optional($user->affiliation_detail)->is_soccer ?? Flags::OFF) == Flags::ON)
<h5 class="text-start mb-3">ご来店人数を選択して下さい。</h5>
<div class="form-group row g-2 mb-3">
    <label for="personCount" class="col-4 col-form-label">ご来店人数<span class="text-danger">*</span></label>
    <div class="col-6">
        <div class="input-group">
            <select class="form-control" id="personCount" placeholder="col-form-label">
                <option value="0">選択してください</option>
                @for($i=1; $i<100; $i++)
                <option value="{!! $i !!}">{!! $i !!}</option>
                @endfor
            </select>
            <div class="input-group-append">
                <span class="input-group-text">人</span>
            </div>
        </div>
    </div>
</div>

<br>

<h5 class="text-start">混雑時、相席をご案内しております。</h5>
<div class="g-2 mb-3">
    <div class="form-check form-check-inline">
        <input class="form-check-input scrs-radio-color" type="radio" name="is_table_share" id="tableShare0" value="1" checked>
        <label class="form-check-label" for="tableShare0">相席可能</label>
    </div>
    <div class="form-check form-check-inline">
        <input class="form-check-input scrs-radio-color" type="radio" name="is_table_share" id="tableShare1" value="0">
        <label class="form-check-label" for="tableShare1">相席不可</label>
    </div>
</div>

<br>
@endif

<h5 class="text-start">ご来店希望日時を選択してください。</h5>

<div class="row g-0 my-2">
    <div class="col-4 text-center"><span class="mdi mdi-circle-outline scrs-text-available"></span><i class="fa-solid fa-ellipsis px-1"></i>予約可能</div>
    <div class="col-4 text-center"><span class="mdi mdi-triangle-outline scrs-text-few-left"></span><i class="fa-solid fa-ellipsis px-1"></i>残りわずか</div>
    <div class="col-4 text-center"><i class="fa-solid fa-minus scrs-text-fully-occupied"></i><i class="fa-solid fa-ellipsis px-1"></i>売り切れ</div>
</div>

<p><small>※ご予約いただいたお客様につきましても、混雑時はお待ちいただく場合がございます。</small></p>

{{-- 予約状況カレンダー（ここから） --}}
{{-- カレンダーコントロール --}}
<div class="row g-0 my-3">
    <div class="col-3 text-end fs-3"><a href="/reserve/visit/{!! $start_date->copy()->addDay(-7)->format('Y-m-d') !!}" data-action="previousWeek"><i class="fa-solid fa-angles-left"></i></a></div>
    <div class="col-6 text-center fs-3">{!! $start_date->format('m/d') !!}～{!! $end_date->format('m/d') !!}</div>
    <div class="col-3 fs-3"><a href="/reserve/visit/{!! $start_date->copy()->addDay(7)->format('Y-m-d') !!}" data-action="nextWeek"><i class="fa-solid fa-angles-right"></i></a></div>
</div>

{{-- 曜日 --}}
<table class="w-100" style="border-collapse:separate;border-spacing:1px;">
<colgroup>
    <col style="width:16%;">
    <col style="width:12%;">
    <col style="width:12%;">
    <col style="width:12%;">
    <col style="width:12%;">
    <col style="width:12%;">
    <col style="width:12%;">
    <col style="width:12%;">
</colgroup>
<thead>
<tr class="scrs-bg">
    <th class="text-center align-middle">&nbsp;</th>
    <th class="text-center align-middle"><span class="text-danger">日<span></th>
    <th class="text-center align-middle">月</th>
    <th class="text-center align-middle">火</th>
    <th class="text-center align-middle">水</th>
    <th class="text-center align-middle">木</th>
    <th class="text-center align-middle">金</th>
    <th class="text-center align-middle"><span class="text-primary">土</span></th>
</tr>
</thead>
</table>

{{-- 予約状況 --}}
<table class="w-100" style="border-collapse:separate;border-spacing:2px;border-color: #72777a;">
<colgroup>
    <col style="width:16%;">
    <col style="width:12%;">
    <col style="width:12%;">
    <col style="width:12%;">
    <col style="width:12%;">
    <col style="width:12%;">
    <col style="width:12%;">
    <col style="width:12%;">
</colgroup>
<thead>
<tr>
    <th class="bg-white text-center align-middle py-1">&nbsp;</th>
    @foreach($dates as $date)
    <th class="bg-white text-center align-middle py-1"><small>{!! $date->date->format('m/d') !!}</small></th>
    @endforeach
</tr>
</thead>
<tbody>
@foreach($time_schedules as $time_schedule)
@eval(list($hour, $minute, $second) = explode(':', $time_schedule->time))
<tr>
    <td class="bg-white text-center align-middle py-1">{{ sprintf('%02d:%02d', $hour, $minute) }}</td>
    @foreach($dates as $date)
    <td class="bg-white text-center align-middle py-1"><span data-action="toggle" data-time_schedule_id="{!! $time_schedule->id !!}" data-date="{!! $date->date->format('Y-m-d') !!}" class="mdi mdi-circle-outline scrs-text-available fs-4"></span></td>
    @endforeach
</tr>
@endforeach
</tbody>
</table>
{{-- 予約状況カレンダー（ここまで） --}}

<br>

<div class="d-flex justify-content-center py-2">
    <button data-action="reserve" type="button" class="btn scrs-bg-main-button col-8" data-bs-toggle="modal" data-bs-target="#reserveConfirm">予約する</button>
</div>

<div class="d-flex justify-content-center py-2">
    <a class="btn border border-1 scrs-bg-sub-button scrs-border-main col-8" href="/reserve">戻る</a>
</div>

@endsection

<x-confirm-dialog id="reserveConfirm" type="reserved">
    <x-slot name="title">確認</x-slot>
    <h3 data-name="message" class="text-center mb-3">1月8日(土)　09:50～</h3>
    <p data-name="description" class="text-center">※混雑時はお待ちいただく場合がございます</p>
    <x-slot name="ok_button">予約を確定する</x-slot>
    <x-slot name="cancel_button">戻る</x-slot>
</x-confirm-dialog>
