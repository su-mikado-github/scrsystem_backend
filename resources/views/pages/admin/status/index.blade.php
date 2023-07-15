@extends('layouts.admin')

@use(Carbon\Carbon)
@use(App\AffiliationDetailTypes)
@use(App\Flags)
@use(App\Weekdays)
@use(App\SortTypes)

<x-script>
import { SCRSPage } from "/scrs-pages.js";
import { SCRSConfirmDialog } from "/dialogs/confirm-dialog.js";

class AdminStatusPage extends SCRSPage {

    #daily = null;

    constructor() {
        super();
        //
        this.#daily = this.actions("daily", [ "click" ]);
    }

    daily_click(e) {
        const date = e.target.dataset["date"];
        this.forward([ "/admin/status/daily", date ]);
    }
}

SCRSPage.startup(()=>new AdminStatusPage());
</x-script>

@section('page.title')
<span>管理画面<span><x-icon name="fa-solid fa-angles-right" /><span>利用状況<span>
@endsection

@section('side-menu')
<x-virtical-menu id="idLeftSideMenu" category="left.side.menu" item-key="status" />
@endsection

@section('main')
<div class="row">
    <div class="col-8">
        <div class="row">
            <div class="col-4 text-end">
                <a class="btn btn-link text-body" href="{!! url('/admin/status') !!}?year_month={!! $month_calendar->previous_date->format('Y-m') !!}">≪前月</a>
            </div>
            <div class="col-2 text-center py-2">
                <span class="text-nowrap3">{!! $month_calendar->date->format('Y年m月') !!}</span>
            </div>
            <div class="col-2 text-center">
                <a class="btn btn-link text-body" href="{!! url('/admin/status') !!}">当月</a>
            </div>
            <div class="col-4 text-start">
                <a class="btn btn-link text-body" href="{!! url('/admin/status') !!}?year_month={!! $month_calendar->next_date->format('Y-m') !!}">次月≫</a>
            </div>
        </div>
    </div>
    <div class="col-4 text-end">
        &nbsp;
    </div>
</div>

<br>
<table class="table table-bordered table-hover" style="width:70%;">
<colgroup>
    <col style="width:4.0%;">
    <col style="width:4.0%;">
    <col style="width:11%;">
    <col style="width:11%;">
    <col style="width:11%;">
    <col style="width:11%;">
    <col style="width:11%;">
    <col style="width:11%;">
    <col style="width:11%;">
    <col style="width:11%;">
    <col style="">
</colgroup>
<thead class="scrs-bg-main">
    <tr>
        <th class="text-center align-bottom" style="width:3em;" rowspan="2">日</th>
        <th class="text-center align-bottom" style="width:3em;" rowspan="2">曜</th>
        <th colspan="4">食堂</th>
        <th colspan="4">お弁当</th>
        <th style="width:3em;" rowspan="2">&nbsp;</th>
    </tr>
    <tr>
        <th>予約人数</th>
        <th>取消人数</th>
        <th>利用人数</th>
        <th><small>サッカー利用率</small></th>
        <th>予約個数</th>
        <th>取消個数</th>
        <th>受取個数</th>
        <th><small>サッカー利用率</small></th>
    </tr>
</thead>
<tbody>
    @foreach($calendars as $calendar)
    @php
        $soccer_user_count = $calendar->soccer_user_count;
        $soccer_reserve_count = $calendar->soccer_reserve_count;
        $lunchbox_reserve_count = $calendar->lunchbox_reserve_count;
    @endphp
    <tr>
        <td class="text-center bg-white">{!! sprintf('%2d', $calendar->day) !!}</td>
        <td class="text-center bg-white">{{ Weekdays::of($calendar->weekday)->ja }}</td>
        <td class="text-end bg-white">{{ op($calendar->dining_hall_reserve_summary)->reserve_count ?? 0 }}&nbsp;人</td>
        <td class="text-end bg-white">{{ op($calendar->dining_hall_reserve_summary)->cancel_reserve_count ?? 0 }}&nbsp;人</td>
        <td class="text-end bg-white">{{ op($calendar->dining_hall_reserve_summary)->checkin_reserve_count ?? 0 }}&nbsp;人</td>
        <td class="text-end bg-white">{{ (!$soccer_user_count ? 0 : floor($soccer_reserve_count * 100 / $soccer_user_count)) }}&nbsp;％</td>
        <td class="text-end bg-white">{{ op($calendar->lunchbox_reserve_summary)->reserve_count ?? 0 }}&nbsp;個</td>
        <td class="text-end bg-white">{{ op($calendar->lunchbox_reserve_summary)->cancel_reserve_count ?? 0 }}&nbsp;個</td>
        <td class="text-end bg-white">{{ op($calendar->lunchbox_reserve_summary)->checkin_reserve_count ?? 0 }}&nbsp;個</td>
        <td class="text-end bg-white">{{ (!$soccer_user_count ? 0 : floor($lunchbox_reserve_count * 100 / $soccer_user_count)) }}&nbsp;％</td>
        <td class="text-center bg-white"><x-icon name="fa-solid fa-magnifying-glass" style="font-size:22px;margin:0!important;cursor:pointer;" data-action="daily" data-date="{!! $calendar->date->format('Y-m-d') !!}" /></td>
    </tr>
    @endforeach
</tbody>
</table>
@endsection
