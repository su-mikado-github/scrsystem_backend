@use(Carbon\Carbon)
@use(App\ReserveTypes)
@use(App\Weekdays)
@extends('layouts.default')

<x-script>
import { SCRSPage } from "/scrs-pages.js";
import { SCRSConfirmDialog } from "/dialogs/confirm-dialog.js";

class ChangePage extends SCRSPage {
    constructor() {
        super();
        //
    }
}

SCRSPage.startup(()=>new ChangePage());
</x-script>

@section('page.title')
ご予約日の変更／キャンセル
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

<div class="px-5 py-4 scrs-sheet-normal">
    <h3 class="text-center mb-4">ご予約内容</h3>
    <p>※ご予約はされていません。</p>
</div>

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
<table class="w-100" style="border-collapse:separate;border-spacing:1px;">
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
                    $is_dish_menu = ($calendar->dish_menus->count() > 0);
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
@endsection
