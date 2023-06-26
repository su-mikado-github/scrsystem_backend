@extends('layouts.default')

@use(Carbon\Carbon)

@use(App\DishTypes)
@use(App\ReserveTypes)
@use(App\Weekdays)

<x-script>
import { SCRSPage } from "/scrs-pages.js";
import { SCRSConfirmDialog } from "/dialogs/confirm-dialog.js";

class ReserveLunchboxPage extends SCRSPage {
    #previousMonth = null;
    #nextMonth = null;
    #today = null;

    #lunchboxCount = null;
    #reserve = null;

    #reservedDialog = null;

    constructor() {
        super();
        //
        this.#previousMonth = this.action("previousMonth", [ "click" ]);
        this.#nextMonth = this.action("nextMonth", [ "click" ]);
        this.#today = this.action("today", [ "click" ]);

        this.#lunchboxCount = this.field("lunchboxCount").handle("change");
        this.#reserve = this.action("reserve", [ "click" ]);

        this.#reservedDialog = new SCRSConfirmDialog(this, "reserveConfirm", null, [ "ok" ]);
    }

    lunchboxCount_change(e) {
        const lunchboxCount = this.#lunchboxCount.value;
        this.#reserve.disabled = (lunchboxCount == 0);
    }

    reserve_click(e) {
        const lunchboxCount = this.#lunchboxCount.value;
        if (lunchboxCount) {
            this.#reservedDialog.field("message").html(`${lunchboxCount}個のお弁当を予約します。<br>よろしいですか？`);
            this.#reservedDialog.open();
        }
    }

    reserveConfirm_ok(e) {
        this.post("{!! route('reserve.lunchbox', [ 'date'=>$day_calendar->date->format('Y-m-d') ]) !!}");
    }

    previousMonth_click(e) {
        this.waitScreen(true);
    }

    nextMonth_click(e) {
        this.waitScreen(true);
    }

    today_click(e) {
        this.waitScreen(true);
    }
}

SCRSPage.startup(()=>new ReserveLunchboxPage());
</x-script>

@section('page.title')
ご予約内容
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

<br>

@isset($reserve)
<div class="px-5 py-4 scrs-sheet-normal">
    <h3 class="text-center mb-4">ご予約内容（{{ ReserveTypes::of($reserve->type)->title }}）</h3>
    <dl class="scrs-item-group mb-0">
        <dt class="label">日時</dt>
        <dd class="item"><span>{{ $reserve->date->format('m月d日') }}</span>@isset($reserve->time)<span class="px-2"></span><span>{{ $reserve->time }}～</span>@endisset</dd>
        <dt class="label">個数</dt>
        <dd class="item"><span>{{ $reserve->reserve_count }}</span>個</dd>
    </dl>
</div>
@else
<h5 class="text-start mb-3">ご注文数を選択して下さい。</h5>
<div class="form-group row g-2 mb-3">
    <label for="reserveCount" class="col-4 col-form-label">ご注文数<span class="text-danger">*</span></label>
    <div class="col-6">
        <div class="input-group">
            <select class="form-control" id="lunchboxCount" name="lunchbox_count" data-field="lunchboxCount" placeholder="col-form-label">
                <option value="0">選択してください</option>
                @for($i=1; $i<100; $i++)
                <option value="{!! $i !!}" {!! (old('lunchbox_count', 0)==$i ? 'selected' : '') !!}>{!! $i !!}</option>
                @endfor
            </select>
            <div class="input-group-append">
                <span class="input-group-text">個</span>
            </div>
        </div>
    </div>
</div>
@endisset

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
    <dd class="text-start mb-0"><span class="fw-bold">太字</span><x-icon name="fa-solid fa-ellipsis" class="mx-1" />弁当の予約済</dd>
    <dd class="text-start mb-0"><span class="text-primary">赤字</span><x-icon name="fa-solid fa-ellipsis" class="mx-1" />日曜日</dd>
    <dd class="text-start mb-0"><span class="text-danger">青字</span><x-icon name="fa-solid fa-ellipsis" class="mx-1" />土曜日</dd>
    </dl>
</div>
</div>

{{-- カレンダーコントロール --}}
<br>
<div class="row g-0 mb-3">
    <div class="col-2 text-center fs-3"><a data-action="previousMonth" href="{!! route('reserve.lunchbox', [ 'date'=>$previous_date->format('Y-m-d') ]) !!}"><i class="fa-solid fa-angles-left text-body"></i></a></div>
    <div class="col-5 text-center fs-3">{!! Carbon::parse($month_calendar->start_date)->format('n/j') !!}～{!! Carbon::parse($month_calendar->end_date)->format('n/j') !!}</div>
    <div class="col-3 text-center fs-3"><a data-action="today" class="text-body" href="{!! route('reserve.lunchbox') !!}"><u>本日</u></a></div>
    <div class="col-2 text-center fs-3"><a data-action="nextMonth" href="{!! route('reserve.lunchbox', [ 'date'=>$next_date->format('Y-m-d') ]) !!}"><i class="fa-solid fa-angles-right text-body"></i></a></div>
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
                    $calendar_reserve = $calendar->reserves()->enabled()->lunchboxBy()->unCanceled()->userBy($user)->first();
                    $is_reserved = isset($calendar_reserve);
                    $is_checkin = isset(op($calendar_reserve)->checkin_dt);
                    $is_past = ($calendar->date < today()->copy()->addDays(2));
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
                    <a class="{!! $text_color !!}" href="{!! route('reserve.lunchbox', [ 'date'=>$calendar->date->format('Y-m-d') ]) !!}">{!! $calendar->date->format('n/j') !!}</a>
                </td>
                @else
                <td class="text-center align-middle py-1 {!! $bg_color !!}">
                    <a class="{!! $text_color !!}" href="{!! route('reserve.lunchbox', [ 'date'=>$calendar->date->format('Y-m-d') ]) !!}">{!! $calendar->date->format('n/j') !!}</a>
                </td>
                @endif
            @endforeach
            </tr>
        @endforeach
    </tbody>
</table>

<br>

@isset($reserve)
<div class="d-flex justify-content-center py-2">
    <span class="btn btn-secondary col-8">既に予約済みです。</span>
</div>
@else
<div class="d-flex justify-content-center py-2">
    <button data-action="reserve" type="button" class="btn scrs-bg-main-button col-8" disabled>予約する</button>
</div>
@endisset

<div class="d-flex justify-content-center py-2">
    <a class="btn border border-1 scrs-bg-sub-button scrs-border-main col-8" href="/reserve">戻る</a>
</div>

@if(isset($day_calendar) && $day_calendar->dish_menus()->dishTypesBy([ DishTypes::LUNCHBOX, DishTypes::BOUT_LUNCHBOX ])->count() > 0)
    @foreach([ DishTypes::LUNCHBOX, DishTypes::BOUT_LUNCHBOX ] as $dish_type)
    @if($day_calendar->dish_menus()->dishTypeBy($dish_type)->count() > 0)
    <br>

    <div class="card">
        <div class="card-header p-1">
            <h5 class="mb-0">{{ DishTypes::of($dish_type)->column_value }}</h5>
        </div>
        <div class="card-body p-1">
            @foreach($day_calendar->dish_menus()->dishTypeBy($dish_type)->orderBy('display_order')->get() as $dish_menu)
            <h6 class="mb-0">{{ $dish_menu->name }}</h6>
            <div class="scrs-sheet-normal ps-4 pe-2 py-2 d-flex justify-content-center mb-2">
                <table class="col-12 col-sm-11 col-md-10 col-lg-8 col-xl-6">
                <tbody>
                <tr>
                    <th class="">エネルギー</th>
                    <td class="text-nowrap text-end">{{ number_format($dish_menu->energy, 1) }}<b class="d-inline-block" style="font-size:80%;width:2em;">kcal</b></td>
                    <th class=""></th>
                    <td class="text-nowrap text-end"></td>
                </tr>
                <tr>
                    <th class="">炭水化物</th>
                    <td class="text-nowrap text-end">{{ number_format($dish_menu->carbohydrates, 1) }}<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                    <th class="">たんぱく質</th>
                    <td class="text-nowrap text-end">{{ number_format($dish_menu->protein, 1) }}<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
                </tr>
                <tr>
                    <th class="">脂質</th>
                    <td class="text-nowrap text-end">{{ number_format($dish_menu->lipid, 1) }}<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                    <th class="">食物繊維</th>
                    <td class="text-nowrap text-end">{{ number_format($dish_menu->dietary_fiber, 1) }}<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
                </tr>
                </tbody>
                </table>
            </div>
            @endforeach

        </div>
        <div class="card-footer bg-transparent p-1">
            @eval($daily_dish_menu = $day_calendar->daily_dish_menus()->dishTypeBy($dish_type)->first())
            <h6 class="mb-0">total</h6>
            <div class="scrs-sheet-normal ps-4 pe-2 py-2 d-flex justify-content-center mb-2">
                <table class="col-12 col-sm-11 col-md-10 col-lg-8 col-xl-6">
                <tbody>
                <tr>
                    <th class="">エネルギー</th>
                    <td class="text-nowrap text-end">{{ number_format($daily_dish_menu->energy, 1) }}<b class="d-inline-block" style="font-size:80%;width:2em;">kcal</b></td>
                    <th class=""></th>
                    <td class="text-nowrap text-end"></td>
                </tr>
                <tr>
                    <th class="">炭水化物</th>
                    <td class="text-nowrap text-end">{{ number_format($daily_dish_menu->carbohydrates, 1) }}<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                    <th class="">たんぱく質</th>
                    <td class="text-nowrap text-end">{{ number_format($daily_dish_menu->protein, 1) }}<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
                </tr>
                <tr>
                    <th class="">脂質</th>
                    <td class="text-nowrap text-end">{{ number_format($daily_dish_menu->lipid, 1) }}<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                    <th class="">食物繊維</th>
                    <td class="text-nowrap text-end">{{ number_format($daily_dish_menu->dietary_fiber, 1) }}<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
                </tr>
                </tbody>
                </table>
            </div>

        </div>
    </div>
    @endif
    @endforeach
@else
<br>

<h6>※メニューはありません。</h6>
@endif

@endsection

<x-confirm-dialog id="reserveConfirm" type="reserved">
    <x-slot name="title">確認</x-slot>
    <h3 data-field="message" class="text-center mb-3"></h3>
    <x-slot name="ok_button">予約を確定する</x-slot>
    <x-slot name="cancel_button">戻る</x-slot>
</x-confirm-dialog>
