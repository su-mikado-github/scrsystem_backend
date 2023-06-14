@extends('layouts.default')

@use(Carbon\Carbon)
@use(App\Weekdays)
@use(App\DishTypes)

<x-script>
import { SCRSPage } from "/scrs-pages.js";
import { SCRSQrCodeReaderDialog } from "/dialogs/qr-code-reader-dialog.js";

class DishMenuPage extends SCRSPage {
    {{-- #checkin = null;

    #qrCodeReaderDialog = null; --}}

    constructor() {
        super();
        //
        {{-- this.#checkin = this.action("checkin")?.handle("click");

        this.#qrCodeReaderDialog = new SCRSQrCodeReaderDialog(this, "qrCodeReader", null, [ "read" ]); --}}
    }

    {{-- checkin_click(e) {
        //
        this.#qrCodeReaderDialog.open();
    }

    qrCodeReader_read(e) {
        console.log(e.detail);
        alert(e.detail.code);
        this.#qrCodeReaderDialog.close();
    } --}}
}

SCRSPage.startup(()=>new DishMenuPage());
</x-script>

@section('page.title')
メニュー
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

<div class="px-5 py-4 scrs-sheet-normal">
    <h3 class="text-center mb-4">次回のメニュー</h3>
</div>

<br>

{{-- カレンダーコントロール --}}
<div class="row g-0 my-3">
    <div class="col-2 text-center fs-3"><a href="{!! url('/dish_menu') !!}?year_month={!! Carbon::parse($month_calendar->previous_date)->format('Y-m') !!}" data-action="previousMonth"><i class="fa-solid fa-angles-left text-body"></i></a></div>
    <div class="col-5 text-center fs-3">{!! Carbon::parse($month_calendar->start_date)->format('n/j') !!}～{!! Carbon::parse($month_calendar->end_date)->format('n/j') !!}</div>
    <div class="col-3 text-center fs-3"><a class="text-body" href="{!! url('/dish_menu') !!}?date={!! today()->format('Y-m-d') !!}"><u>本日</u></a></div>
    <div class="col-2 text-center fs-3"><a href="{!! url('/dish_menu') !!}?year_month={!! Carbon::parse($month_calendar->next_date)->format('Y-m') !!}" data-action="nextMonth"><i class="fa-solid fa-angles-right text-body"></i></a></div>
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
                    $is_past = ($calendar->date < today());
                    $is_today = ($calendar->date == optional($day_calendar)->date);
                    $is_sunday = ($calendar->weekday == Weekdays::SUNDAY);
                    $is_saturday = ($calendar->weekday == Weekdays::SATURDAY);
                    $is_dish_menu = ($calendar->dish_menus()->count() > 0);
                @endphp
                @if(!$is_dish_menu)
                <td class="text-center align-middle py-1 {!! ($is_today ? 'scrs-bg-today' : 'bg-secondary') !!} {!! ($is_sunday ? 'text-danger' : '') !!} {!! ($is_saturday ? 'text-primary' : '') !!}">
                    <span class="text-body">{!! Carbon::parse($calendar->date)->format('n/j') !!}</span>
                </td>
                @elseif($is_past)
                <td class="text-center align-middle py-1 {!! ($is_today ? 'scrs-bg-today' : 'bg-secondary') !!} {!! ($is_sunday ? 'text-danger' : '') !!} {!! ($is_saturday ? 'text-primary' : '') !!}">
                    <a class="text-body {!! ($is_dish_menu ? 'fw-bold' : '') !!}" href="{!! url('/dish_menu') !!}?year_month={!! $month_calendar->date->format('Y-m') !!}&date={!! $calendar->date->format('Y-m-d') !!}">{!! Carbon::parse($calendar->date)->format('n/j') !!}</a>
                </td>
                @elseif($month_calendar->contains($calendar))
                <td class="text-center align-middle py-1 {!! ($is_today ? 'scrs-bg-today' : 'bg-white') !!} {!! ($is_sunday ? 'text-danger' : '') !!} {!! ($is_saturday ? 'text-primary' : '') !!}">
                    <a class="text-body {!! ($is_dish_menu ? 'fw-bold' : '') !!}" href="{!! url('/dish_menu') !!}?year_month={!! $month_calendar->date->format('Y-m') !!}&date={!! $calendar->date->format('Y-m-d') !!}">{!! Carbon::parse($calendar->date)->format('n/j') !!}</a>
                </td>
                @elseif($month_calendar->isUnder($calendar))
                <td class="text-center align-middle py-1 scrs-bg-lightgray text-secondary">
                    <a class="text-body {!! ($is_dish_menu ? 'fw-bold' : '') !!}" href="{!! url('/dish_menu') !!}?year_month={!! $month_calendar->previous_date->format('Y-m') !!}&date={!! $calendar->date->format('Y-m-d') !!}">{!! Carbon::parse($calendar->date)->format('n/j') !!}</a>
                </td>
                @else
                <td class="text-center align-middle py-1 scrs-bg-lightgray text-secondary">
                    <a class="text-body {!! ($is_dish_menu ? 'fw-bold' : '') !!}" href="{!! url('/dish_menu') !!}?year_month={!! $month_calendar->next_date->format('Y-m') !!}&date={!! $calendar->date->format('Y-m-d') !!}">{!! Carbon::parse($calendar->date)->format('n/j') !!}</a>
                </td>
                @endif
            @endforeach
            </tr>
        @endforeach
    </tbody>
</table>

@if(isset($day_calendar) && $day_calendar->dish_menus()->count() > 0)
    @foreach(DishTypes::values() as $dish_type)
    @if($day_calendar->dish_menus()->dishTypeBy($dish_type->id)->count() > 0)
    <br>

    <div class="card">
        <div class="card-header p-1">
            <h5 class="mb-0">{{ $dish_type->column_value }}</h5>
        </div>
        <div class="card-body p-1">
            @foreach($day_calendar->dish_menus()->dishTypeBy($dish_type->id)->orderBy('display_order')->get() as $dish_menu)
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
            @eval($daily_dish_menu = $day_calendar->daily_dish_menus()->dishTypeBy(DishTypes::DINING_HALL)->first())
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
