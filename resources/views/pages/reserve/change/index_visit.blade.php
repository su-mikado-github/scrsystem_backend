@use(Carbon\Carbon)
@use(App\ReserveTypes)
@use(App\Weekdays)
@extends('layouts.default')

@eval($from_time = transform($reserve->time, function($v) { list($h, $m, $s) = explode(":", $v); return sprintf('%02d:%02d', $h, $m); }));

<x-script>
import { SCRSPage } from "/scrs-pages.js";
import { SCRSConfirmDialog } from "/dialogs/confirm-dialog.js";

class ChangeVisitPage extends SCRSPage {
    #newTime = null;

    #toggles = null;
    #cancel = null;

    #changeConfirmDialog = null;
    #cancelConfirmDialog = null;

    constructor() {
        super();
        //
        this.#newTime = this.field("newTime");

        this.#toggles = this.actions("toggle", [ "click" ]);
        this.#cancel = this.action("cancel", [ "click" ]);

        this.#changeConfirmDialog = new SCRSConfirmDialog(this, "changeConfirm", null, [ "ok" ]);
        this.#cancelConfirmDialog = new SCRSConfirmDialog(this, "cancelConfirm", null, [ "ok" ]);
    }

    toggle_click(e) {
        const time = e.target.dataset["time"];
        this.#newTime.value = time;

        const fromTime = @json($from_time);
        const [ hour, minute, sec ] = time.split(":");
        const message = `${fromTime} → ${hour}:${minute}<br>変更してもよろしいですか？`;
        this.#changeConfirmDialog.field("message").html(message);
        this.#changeConfirmDialog.open();
    }

    cancel_click(e) {
        this.#cancelConfirmDialog.open();
    }

    changeConfirm_ok(e) {
        this.post([ "/reserve/change", @json($reserve->id), 'visit' ]);
    }

    cancelConfirm_ok(e) {
        this.delete([ "/reserve/change", @json($reserve->id) ]);
    }
}

SCRSPage.startup(()=>new ChangeVisitPage());
</x-script>

@section('page.title')
ご予約時間の変更／キャンセル
@endsection

@section('main')
<h2>
    <ruby>
        <rb>{{ $user->last_name }}</rb>
        <rt>{{ $user->last_name_kana }}</rt>
    </ruby>
    <ruby>
      <rb>{{ $user->first_name }}</rb>
      <rt>{{ $user->first_name_kana }}</rt>
    </ruby>
    様
</h2>

@if(isset($reserve))
@eval(list($label, $unit, $action) = ($reserve->type == ReserveTypes::LUNCHBOX ? [ '個数', '個', '受け取る' ] : [ '人数', '人', 'チェックインする' ]))
<br>
<div class="px-5 py-4 scrs-sheet-normal">
    <h3 class="text-center mb-4">ご予約内容（{{ ReserveTypes::of($reserve->type)->title }}）</h3>
    <dl class="scrs-item-group mb-0">
        <dt class="label">日時</dt>
        <dd class="item"><span>{{ $reserve->date->format('m月d日') }}</span>@isset($reserve->time)<span class="px-2"></span><span>{{ $reserve->time }}～</span>@endisset</dd>
        <dt class="label">{{ $label }}</dt>
        <dd class="item"><span>{{ $reserve->reserve_count }}</span>{{ $unit }}</dd>
    </dl>
</div>

<br>
<div class="d-flex justify-content-center">
    @isset($reserve->checkin_dt)
    <span class="btn btn-lg btn-secondary col-10 py-2">完了</span>
    @endisset
</div>
@else
<div class="px-5 py-4 scrs-sheet-normal">
    <h3 class="text-center mb-4">ご予約内容</h3>
    <p>※本日のご予約はありません。</p>
</div>
@endif

<br>
<div class="row">
<div class="col-6 text-center">
    <dl class="d-inline-block mb-0" style="font-size:80%;">
    <dt class="text-start">背景色</dt>
    <dd class="text-start mb-0"><span class="scrs-bg-today">　　</span><x-icon name="fa-solid fa-ellipsis" class="mx-1" />予約対象日</dd>
    <dd class="text-start mb-0"><span class="bg-secondary">　　</span><x-icon name="fa-solid fa-ellipsis" class="mx-1" />予約不可日</dd>
    <dd class="text-start mb-0"><span class="scrs-bg-lightgray">　　</span><x-icon name="fa-solid fa-ellipsis" class="mx-1" />前後の月</dd>
    </dl>
</div>
<div class="col-6 text-center">
    <dl class="d-inline-block mb-0" style="font-size:80%;">
    <dt class="text-start">日付</dt>
    <dd class="text-start mb-0"><span class="text-decoration-underline">下線</span><x-icon name="fa-solid fa-ellipsis" class="mx-1" />メニュー有り</dd>
    <dd class="text-start mb-0"><span class="fw-bold">太字</span><x-icon name="fa-solid fa-ellipsis" class="mx-1" />食堂・弁当の予約済</dd>
    <dd class="text-start mb-0"><span class="text-primary">赤字</span><x-icon name="fa-solid fa-ellipsis" class="mx-1" />日曜日</dd>
    <dd class="text-start mb-0"><span class="text-danger">青字</span><x-icon name="fa-solid fa-ellipsis" class="mx-1" />土曜日</dd>
    </dl>
</div>
</div>

<br>
<p class="m-0">ご予約日（リンク）は、タップして変更できます。</p>

{{-- カレンダーコントロール --}}
<div class="row g-0 my-3">
    <div class="col-2 fs-3 d-flex align-items-center justify-content-center"><a data-action="previousMonth" href="{!! route('reserve.change', [ 'date'=>$previous_date->format('Y-m-d') ]) !!}"><x-icon name="fa-solid fa-angles-left" class="text-body" /></a></div>
    <div class="col-5 fs-3 d-flex align-items-center justify-content-center">{!! Carbon::parse($month_calendar->start_date)->format('n/j') !!}～{!! Carbon::parse($month_calendar->end_date)->format('n/j') !!}</div>
    <div class="col-3 fs-3 d-flex align-items-center justify-content-center"><a data-action="today" class="text-body" href="{!! route('reserve.change') !!}"><u>本日</u></a></div>
    <div class="col-2 fs-3 d-flex align-items-center justify-content-center"><a data-action="nextMonth" href="{!! route('reserve.change', [ 'date'=>$next_date->format('Y-m-d') ]) !!}"><x-icon name="fa-solid fa-angles-right" class="text-body" /></a></div>
</div>

{{-- 曜日 --}}
<table data-field="timeSchedule" class="w-100" style="border-collapse:separate;border-spacing:1px;">
<colgroup>
    <col style="width:14.28%;">
    <col style="width:14.28%;">
    <col style="width:14.28%;">
    <col style="width:14.28%;">
    <col style="width:14.28%;">
    <col style="width:14.28%;">
    <col style="width:14.28%;">
</colgroup>
<thead>
<tr class="scrs-bg">
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

<table class="w-100" style="border-collapse:separate;border-spacing:2px;border-color: #72777a;">
    <colgroup>
        <col style="width:14.28%;">
        <col style="width:14.28%;">
        <col style="width:14.28%;">
        <col style="width:14.28%;">
        <col style="width:14.28%;">
        <col style="width:14.28%;">
        <col style="width:14.28%;">
    </colgroup>
    <tbody>
        @foreach($calendars->chunk(7) as $week)
            <tr>
            @foreach($week as $calendar)
                @php
                    $calendar_reserve = $calendar->reserves()->enabled()->unCanceled()->userBy($user)->first();
                    $is_reserved = isset($calendar_reserve);
                    $is_checkin = isset(op($calendar_reserve)->checkin_dt);
                    $is_past = ($calendar->date < today()->copy()->addDays(1));
                    $is_today = ($calendar->date == $day_calendar->date);
                    $is_dish_menu = ($calendar->dish_menus()->count() > 0);
                    $is_current_month = $month_calendar->contains($calendar);
                    $bg_color = 'bg-white';
                    $text_color = 'text-body';
                    if ($is_past || $is_checkin) {
                        list($bg_color, $text_color) = [ 'bg-secondary','text-body' ];
                    }
                    else if ($is_reserved) {
                        list($bg_color, $text_color) = [ ($is_today ? 'scrs-bg-today' : ($is_current_month ? 'bg-white' : 'scrs-bg-lightgray')), ($is_current_month ? 'text-body' : 'text-secondary') ];
                    }
                    else if ($is_today) {
                        list($bg_color, $text_color) = [ 'scrs-bg-today','text-body' ];
                    }
                    else if ($is_current_month) {
                        if ($calendar->weekday == Weekdays::SUNDAY) {
                            list($bg_color, $text_color) = [ 'bg-white','text-danger' ];
                        }
                        else if ($calendar->weekday == Weekdays::SATURDAY) {
                            list($bg_color, $text_color) = [ 'bg-white','text-primary' ];
                        }
                    }
                    else {
                        list($bg_color, $text_color) = [ 'scrs-bg-lightgray','text-secondary' ];
                    }
                @endphp
                @if($is_past || !$is_dish_menu || $is_checkin)
                <td class="text-center align-middle py-1 {!! $bg_color !!}">
                    <a class="{!! $text_color !!} text-decoration-none" href="#">{!! $calendar->date->format('n/j') !!}</a>
                </td>
                @elseif($is_reserved)
                <td class="text-center align-middle py-1 {!! $bg_color !!}">
                    <a class="{!! $text_color !!} fw-bold" href="{!! route('reserve.change', [ 'date'=>$calendar->date->format('Y-m-d') ]) !!}">{!! $calendar->date->format('n/j') !!}</a>
                </td>
                @elseif($is_current_month)
                <td class="text-center align-middle py-1 {!! $bg_color !!}">
                    <a class="{!! $text_color !!} text-decoration-underline" href="{!! route('reserve.change', [ 'date'=>$calendar->date->format('Y-m-d') ]) !!}">{!! $calendar->date->format('n/j') !!}</a>
                </td>
                @else
                <td class="text-center align-middle py-1 {!! $bg_color !!}">
                    <a class="{!! $text_color !!} text-decoration-underline" href="{!! route('reserve.change', [ 'date'=>$calendar->date->format('Y-m-d') ]) !!}">{!! $calendar->date->format('n/j') !!}</a>
                </td>
                @endif
            @endforeach
            </tr>
        @endforeach
    </tbody>
</table>

@if(isset($reserve) && empty($reserve->checkin_dt) && $reserve->type!=ReserveTypes::LUNCHBOX)
<br>
<p>お時間の変更の際は、下記の<x-icon name="mdi mdi-circle-outline" class="scrs-text-available fs-4" />または<x-icon name="mdi mdi-triangle-outline" class="scrs-text-few-left fs-4" />のいずれかをタップしてください。</p>
<p>※<x-icon name="fa-solid fa-registered" class="scrs-text-available fs-4" />は、現在の予約時間です。</p>

<input type="hidden" data-field="newTime" name="new_time">
<table class="w-100" style="border-collapse:separate;border-spacing:2px;">
<colgroup>
    <col style="width:25%;">
    <col style="width:75%;">
</colgroup>
<thead>
@foreach($time_schedules as $time_schedule)
    @php
        $is_reserved_time = ($reserve->time == $time_schedule->time);
        $empty_seat_rate = (op($empty_states->where('time', $time_schedule->time)->first())->empty_seat_rate ?? 0);
        if ($is_reserved_time) {
            list($icon_name, $class) = [ 'fa-solid fa-registered', 'scrs-text-available' ];
        }
        else if ($empty_seat_rate == 0) {
            list($icon_name, $class) = [ 'fa-solid fa-minus', 'scrs-text-fully-occupied' ];
        }
        else if ($empty_seat_rate < 50) {
            list($icon_name, $class) = [ 'mdi mdi-triangle-outline', 'scrs-text-few-left' ];
        }
        else {
            list($icon_name, $class) = [ 'mdi mdi-circle-outline', 'scrs-text-available' ];
        }
        list($hour, $minute, $second) = explode(':', $time_schedule->time);
    @endphp
    <tr class="scrs-bg">
        <td class="bg-white text-center align-middle py-2">{{ sprintf('%02d:%02d', $hour, $minute) }}</td>
        <td class="bg-white text-center align-middle py-2">
            @if($is_reserved_time)
            <x-icon name="{!! $icon_name !!}" class="{!! $class !!}" data-time="{!! $time_schedule->time !!}"  />
            @else
            <x-icon name="{!! $icon_name !!}" class="{!! $class !!}" data-time="{!! $time_schedule->time !!}" data-action="toggle"  />
            @endif
        </td>
    </tr>
@endforeach
</thead>
</table>

{{-- <div class="d-flex justify-content-center py-2">
    <button data-action="change" type="button" class="btn scrs-bg-main-button col-8">予約変更</button>
</div> --}}
@endif

<br>

<div class="d-flex justify-content-center py-2">
    <button data-action="cancel" type="button" class="btn border border-1 scrs-bg-sub-button scrs-border-main col-8">予約取消</button>
</div>

<div class="d-flex justify-content-center py-2">
    <div class="col-8">
        <small class="scrs-text-main">※予約取消では、完全に予約が取り消されますので、ご注意ください。</small>
    </div>
</div>

@endsection

<x-confirm-dialog id="changeConfirm" type="change">
    <x-slot name="title">確認</x-slot>
    <h3 data-field="message" class="text-center mb-3"></h3>
    <p class="text-center">※混雑時はお待ちいただく場合がございます</p>
    <x-slot name="ok_button">予約を変更する</x-slot>
    <x-slot name="cancel_button">戻る</x-slot>
</x-confirm-dialog>

<x-confirm-dialog id="cancelConfirm" type="cancel">
    <x-slot name="title">確認</x-slot>
    <h3 class="text-center mb-3 text-danger">※完全に予約が取り消されます。<br>よろしいですか？</h3>
    <x-slot name="ok_button">予約を取り消す</x-slot>
    <x-slot name="cancel_button">戻る</x-slot>
</x-confirm-dialog>
