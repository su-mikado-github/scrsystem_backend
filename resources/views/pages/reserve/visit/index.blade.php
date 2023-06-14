@extends('layouts.default')

@use(Carbon\Carbon)
@use(App\AffiliationDetailTypes)
@use(App\DishTypes)
@use(App\Flags)
@use(App\ReserveTypes)
@use(App\Weekdays)

<x-script>
import { SCRSPage } from "/scrs-pages.js";
import { SCRSConfirmDialog } from "/dialogs/confirm-dialog.js";

class ReserveVisitPage extends SCRSPage {
    #previousWeek = null;
    #nextWeek = null;
    #today = null;

    #toggles = null;

    #reserveTime = null;
    @if((optional($user->affiliation_detail)->is_soccer ?? Flags::OFF) == Flags::OFF)
    #isTableShareOk = null;
    #isTableShareNg = null;
    @endif

    #reservedDialog = null;

    constructor() {
        super();

        this.#previousWeek = this.action("previousWeek", [ "click" ]);
        this.#nextWeek = this.action("nextWeek", [ "click" ]);
        this.#today = this.action("today", [ "click" ]);
        this.#toggles = this.actions("toggle", [ "click" ]);
        this.#reserveTime = this.field("reserve_time");
        @if((optional($user->affiliation_detail)->is_soccer ?? Flags::OFF) == Flags::OFF)
        this.#isTableShareOk = this.field("is_table_share_ok");
        this.#isTableShareNg = this.field("is_table_share_ng");
        @endif
        this.#reservedDialog = new SCRSConfirmDialog(this, "reserveConfirm", null, [ "show", "hide", "ok" ]);
    }

    reserveConfirm_show(e) {
//        e.preventDefault();
    }

    reserveConfirm_hide(e) {

    }

    reserveConfirm_ok(e) {
        this.#reservedDialog.close();

        this.waitScreen(true);
        this.post([ "/reserve/visit", e.detail.date ]);
    }

    toggle_click(e) {
        e.preventDefault();
        e.stopPropagation();
        const emptySeatRate = e.target.dataset["empty_seat_rate"] | 0;
        if (emptySeatRate > 0) {
            const date = dayjs(e.target.dataset["date"]);
            const time = e.target.dataset["time"];
            {{-- date.weekday(-7) --}}
            this.#reserveTime.value = time;
            this.#reservedDialog.field("message").text(`${date.format("MM月DD日")}(${date.weekday().ja})　${time}～`);
            @if((optional($user->affiliation_detail)->is_soccer ?? Flags::OFF) == Flags::OFF)
            const isTableShareNg = this.#isTableShareNg.checked;
            this.#reservedDialog.field("table_share_annotation").rcss(isTableShareNg, "d-none");
            @endif
            this.#reservedDialog.open({ date: date.format("YYYY-MM-DD") });
        }
    }

    previousWeek_click(e) {
        this.waitScreen(true);
    }

    nextWeek_click(e) {
        this.waitScreen(true);
    }

    today_click(e) {
        this.waitScreen(true);
    }
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
<input type="hidden" name="reserve_time" data-field="reserve_time">
@if((optional($user->affiliation_detail)->is_soccer ?? Flags::OFF) == Flags::OFF)
<h5 class="text-start mb-3">ご来店人数を選択して下さい。</h5>
<div class="form-group row g-2 mb-3">
    <label for="personCount" class="col-4 col-form-label">ご来店人数<span class="text-danger">*</span></label>
    <div class="col-6">
        <div class="input-group">
            <select class="form-control" id="personCount" name="person_count" placeholder="col-form-label">
                <option value="0">選択してください</option>
                @for($i=1; $i<$seat_count; $i++)
                <option value="{!! $i !!}" {!! (old('parson_count', 0)==$i ? 'selected' : '') !!}>{!! $i !!}</option>
                @endfor
            </select>
            <div class="input-group-append">
                <span class="input-group-text">人</span>
            </div>
        </div>
        @error('person_count')<p class="text-danger">{{ $message }}</p>@enderror
    </div>
</div>

<br>

<h5 class="text-start">混雑時、相席をご案内しております。</h5>
<div class="g-2 mb-3">
    <div class="form-check form-check-inline">
        <input class="form-check-input scrs-radio-color" type="radio" name="is_table_share" id="tableShare0" data-field="is_table_share_ok" value="1" {!! (old('is_table_share', Flags::OFF)==Flags::ON ? 'checked' : '') !!}>
        <label class="form-check-label" for="tableShare0">相席可能</label>
    </div>
    <div class="form-check form-check-inline">
        <input class="form-check-input scrs-radio-color" type="radio" name="is_table_share" id="tableShare1" data-field="is_table_share_ng" value="0" {!! (old('is_table_share', Flags::OFF)==Flags::OFF ? 'checked' : '') !!}>
        <label class="form-check-label" for="tableShare1">相席不可</label>
    </div>
    @error('is_table_share')<p class="text-danger">{{ $message }}</p>@enderror
</div>

<br>
@endif

<h5 class="text-start">ご来店希望日時を選択してください。</h5>

<div class="row g-0 my-2">
    <div class="col-4 text-center"><span class="mdi mdi-circle-outline scrs-text-available"></span><i class="fa-solid fa-ellipsis px-1"></i>予約可能</div>
    <div class="col-4 text-center"><span class="mdi mdi-triangle-outline scrs-text-few-left"></span><i class="fa-solid fa-ellipsis px-1"></i>残りわずか</div>
    <div class="col-4 text-center"><i class="fa-solid fa-minus scrs-text-fully-occupied"></i><i class="fa-solid fa-ellipsis px-1"></i>売り切れ</div>
    <div class="col-4 text-center"><span class="fa-solid fa-registered scrs-text-available"></span><i class="fa-solid fa-ellipsis px-1"></i>予約済</div>
</div>

<p>
    <small>※ご予約いただいたお客様につきましても、混雑時はお待ちいただく場合がございます。</small><br>
    <small>※ご予約したい日時をタップしてください。</small>
</p>

{{-- 予約状況カレンダー（ここから） --}}
{{-- カレンダーコントロール --}}
<div class="row g-0 my-3">
    <div class="col-2 text-end fs-3"><a data-action="previousWeek" href="/reserve/visit/{!! $start_date->copy()->addDay(-7)->format('Y-m-d') !!}"><u><i class="fa-solid fa-angles-left text-body"></i></u></a></div>
    <div class="col-5 text-center fs-3">{!! $start_date->format('m/d') !!}～{!! $end_date->format('m/d') !!}</div>
    <div class="col-3 text-center fs-3"><a data-action="today" class="text-body" href="{!! route('reserve.visit') !!}"><u>本日</u></a></div>
    <div class="col-2 fs-3"><a data-action="nextWeek" href="/reserve/visit/{!! $start_date->copy()->addDay(7)->format('Y-m-d') !!}"><u><i class="fa-solid fa-angles-right text-body"></i></u></a></div>
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
    @foreach($calendars as $calendar)
    @php
        $is_today = ($calendar->date == $day_calendar->date);
        $text_color = 'text-body';
        if ($calendar->weekday == Weekdays::SATURDAY) {
            $text_color = 'text-primary';
        }
        else if ($calendar->weekday == Weekdays::SUNDAY) {
            $text_color = 'text-danger';
        }
    @endphp
    <th class="{!! ($is_today ? 'scrs-bg-today' : 'bg-white') !!} text-center align-middle py-1 {!! $text_color !!}"><small>{!! $calendar->date->format('m/d') !!}</small></th>
    @endforeach
</tr>
</thead>
<tbody>
@foreach($time_schedules as $time_schedule)
@eval(list($hour, $minute, $second) = explode(':', $time_schedule->time))
<tr>
    <td class="bg-white text-center align-middle py-1">{{ sprintf('%02d:%02d', $hour, $minute) }}</td>
    @foreach($calendars as $calendar)
        @php
            $is_past = ($calendar->date < today()->copy()->addDays(2));
            $is_today = ($calendar->date == $day_calendar->date);
            $calendar_date = $calendar->date;
            $is_reserved = $reserves->where('date', '=', $calendar_date)->isNotEmpty();
            $is_reserved_time = $reserves->filter(function($reserve) use($calendar, $time_schedule) {
                return ($reserve->date == $calendar->date && $reserve->time <= $time_schedule->time && $time_schedule->time <= $reserve->end_time);
            })->isNotEmpty();
            $empty_state = $empty_states->where('date', $calendar->date->format('Y-m-d'))->where('time', $time_schedule->time)->first();
            $empty_seat_rate = (op($empty_state)->empty_seat_rate ?? 0);
            if ($is_reserved_time) {
                $seat_state = 'fa-solid fa-registered scrs-text-available';
            }
            else if ($empty_seat_rate == 0) {
                $seat_state = 'fa-solid fa-minus scrs-text-fully-occupied';
            }
            else if ($empty_seat_rate < 50) {
                $seat_state = 'mdi mdi-triangle-outline scrs-text-few-left';
            }
            else {
                $seat_state = 'mdi mdi-circle-outline scrs-text-available';
            }
        @endphp
        <!-- {!! print_r(compact('calendar_date', 'is_reserved', 'is_reserved_time'), true) !!} -->
        @if($is_reserved)
        <td class="text-center align-middle py-1 {!! ($is_today ? 'scrs-bg-today' : 'bg-white') !!}">
            <x-icon data-empty_seat_rate="{!! $empty_seat_rate !!}" data-time="{!! $time_schedule->time !!}" data-date="{!! $calendar->date->format('Y-m-d') !!}" class="fs-4 {!! ($is_reserved_time ? 'text-secondary' : 'invisible') !!}" name="{!! $seat_state !!}" />
        </td>
        @elseif($is_past)
        <td class="bg-light text-center align-middle py-1">
            <x-icon data-empty_seat_rate="{!! $empty_seat_rate !!}" data-time="{!! $time_schedule->time !!}" data-date="{!! $calendar->date->format('Y-m-d') !!}" class="fs-4 invisible" name="{!! $seat_state !!}" />
        </td>
        @else
        <td class="{!! (isset($empty_state) ? 'bg-white' : 'bg-secondary') !!} text-center align-middle py-1">
            <x-icon data-action="toggle" data-empty_seat_rate="{!! $empty_seat_rate !!}" data-time="{!! $time_schedule->time !!}" data-date="{!! $calendar->date->format('Y-m-d') !!}" class="fs-4 {!! (isset($empty_state) ? '' : 'invisible') !!}" name="{!! $seat_state !!}" />
        </td>
        @endif
    @endforeach
</tr>
@endforeach
</tbody>
</table>
{{-- 予約状況カレンダー（ここまで） --}}

<br>

<div class="d-flex justify-content-center py-2">
    <a class="btn border border-1 scrs-bg-sub-button scrs-border-main col-8" href="/reserve">戻る</a>
</div>

{{--
@if(isset($day_calendar) && $day_calendar->dish_menus()->dishTypesBy([ DishTypes::DINING_HALL ])->count() > 0)
    @foreach([ DishTypes::DINING_HALL ] as $dish_type)
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
@endif --}}

@endsection

<x-confirm-dialog id="reserveConfirm" type="reserved">
    <x-slot name="title">確認</x-slot>
    <h3 data-field="message" class="text-center mb-3">1月8日(土)　09:50～</h3>
    <p data-field="description" class="text-center">※混雑時はお待ちいただく場合がございます</p>
    <p data-field="table_share_annotation" class="text-center d-none">※相席不可を選択されましたが、混雑時はご協力をお願いする場合がございます。<br>ご了承ください。</p>
    <x-slot name="ok_button">予約を確定する</x-slot>
    <x-slot name="cancel_button">戻る</x-slot>
</x-confirm-dialog>
