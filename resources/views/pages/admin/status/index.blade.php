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

    constructor() {
        super();
        //
    }
}

SCRSPage.startup(()=>new AdminStatusPage());
</x-script>

@section('page.title')
<span>管理画面<span><x-icon name="fa-solid fa-angles-right" /><span>利用状況<span>
@endsection

@section('side-menu')
<x-virtical-menu id="idLeftSideMenu" category="left.side.menu" item-key="users" />
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
        <th>利用率　</th>
        <th>予約個数</th>
        <th>取消個数</th>
        <th>受取個数</th>
        <th>利用率　</th>
    </tr>
</thead>
<tbody>
    @foreach($calendars as $calendar)
    <tr>
        <td class="text-center">{!! sprintf('%2d', $calendar->day) !!}</td>
        <td class="text-center">{{ Weekdays::of($calendar->weekday)->ja }}</td>
        <td class="text-end">{{ op($calendar->dining_hall_reserve_summary)->reserve_count ?? 0 }}&nbsp;人</td>
        <td class="text-end">{{ op($calendar->dining_hall_reserve_summary)->cancel_reserve_count ?? 0 }}&nbsp;人</td>
        <td class="text-end">{{ op($calendar->dining_hall_reserve_summary)->checkin_reserve_count ?? 0 }}&nbsp;人</td>
        <td></td>
        <td class="text-end">{{ op($calendar->lunchbox_reserve_summary)->reserve_count ?? 0 }}&nbsp;個</td>
        <td class="text-end">{{ op($calendar->lunchbox_reserve_summary)->cancel_reserve_count ?? 0 }}&nbsp;個</td>
        <td class="text-end">{{ op($calendar->lunchbox_reserve_summary)->checkin_reserve_count ?? 0 }}&nbsp;個</td>
        <td></td>
        <td></td>
    </tr>
    @endforeach
</tbody>
</table>
{{-- <input type="hidden" data-field="fullNameSort" name="full_name_sort" value="{!! $full_name_sort ?? SortTypes::ASC !!}">
<input type="hidden" data-field="affiliationSort" name="affiliation_sort" value="{!! $affiliation_sort ?? SortTypes::ASC !!}">
<input type="hidden" data-field="affiliationDetailSort" name="affiliation_detail_sort" value="{!! $affiliation_detail_sort ?? SortTypes::ASC !!}">
<input type="hidden" data-field="schoolYearSort" name="school_year_sort" value="{!! $school_year_sort ?? SortTypes::ASC !!}">
<input type="hidden" data-field="sortOrders" name="sort_orders" value="{!! $sort_orders->join(',') !!}"> --}}

{{-- <table class="table table-bordered table-hover">
<thead class="scrs-bg-main">
    <tr>
        <th style="width:10em;">
            <div class="d-flex">
                <div class="flex-grow-1">氏名</div>
                <div>
                    <img src="/images/icons/sort_white.svg" style="height:24px;" usemap="#full_name_sort_map">
                    <map name="full_name_sort_map"><area shape="rect" coords="0,0,24,12" data-action="fullNameSortAsc"><area shape="rect" coords="0,12,24,24" data-action="fullNameSortDesc"></map>
                </div>
            </div>
        </th>
        <th class="d-flex flex-row">
            <div class="d-flex col-6">
                <div class="flex-grow-1">所属</div>
                <div>
                    <img src="/images/icons/sort_white.svg" style="height:24px;" usemap="#affiliation_sort_map">
                    <map name="affiliation_sort_map"><area shape="rect" coords="0,0,24,12" data-action="affiliationSortAsc"><area shape="rect" coords="0,12,24,24" data-action="affiliationSortDesc"></map>
                </div>
            </div>
            <div class="d-flex col-6">
                <div class="flex-grow-1">&nbsp;</div>
                <div>
                    <img src="/images/icons/sort_white.svg" style="height:24px;" usemap="#affiliation_detail_sort_map">
                    <map name="affiliation_detail_sort_map"><area shape="rect" coords="0,0,24,12" data-action="affiliationDetailSortAsc"><area shape="rect" coords="0,12,24,24" data-action="affiliationDetailSortDesc"></map>
                </div>
            </div>
        </th>
        <th style="width:6em;">
            <div class="d-flex">
                <div class="flex-grow-1">学年</div>
                <div>
                    <img src="/images/icons/sort_white.svg" style="height:24px;" usemap="#school_year_sort_map">
                    <map name="school_year_sort_map"><area shape="rect" coords="0,0,24,12" data-action="schoolYearSortAsc"><area shape="rect" coords="0,12,24,24" data-action="schoolYearSortDesc"></map>
                </div>
            </div>
        </th>
        <th style="width:4em;">年齢</th>
        <th>メールアドレス</th>
        <th style="width:10em;">電話番号</th>
        <th style="width:5em;">&nbsp;</th>
    </tr>
</thead>
<tbody>
    @foreach($users as $row)
    <tr class="{!! ($row->is_initial_setting==Flags::OFF ? 'table-secondary' : 'bg-white') !!}">
        <td>@if($row->is_initial_setting==Flags::OFF) ※マイページ設定なし @else{{ $row->last_name ?? ' ' }} {{ $row->first_name ?? ' ' }}@endif</td>
        <td><div class="d-flex"><span class="col-6">{{ op($row->affiliation)->name ?? ' ' }}</span><span class="col-6">{{ op($row->affiliation_detail)->name ?? ' ' }}</span></div></td>
        <td class="text-center">{{ op($row->school_year)->name ?? ' ' }}</td>
        <td class="text-end">{{ $row->age ?? ' ' }}</td>
        <td>{{ $row->email ?? ' ' }}</td>
        <td>{{ $row->telephone_no ?? ' ' }}</td>
        <td class="px-0" style="width:5em;">
            <div class="d-flex flex-row">
                <div class="col-6 text-center"><x-icon name="fa-solid fa-magnifying-glass" style="font-size:22px;margin:0!important;cursor:pointer;" data-action="show" data-id="{!! $row->id !!}" /></div>
                @if(op($row->affiliation)->detail_type==AffiliationDetailTypes::INTERNAL)
                <div class="col-6 text-center"><x-icon name="fa-solid fa-trash-can" class="text-danger" style="font-size:22px;margin:0!important;cursor:pointer;" data-action="remove" data-id="{!! $row->id !!}"
                    data-first_name="{{ $row->first_name }}" data-last_name="{{ $row->last_name }}" data-affiliation_name="{{ op($row->affiliation)->name ?? '' }}"
                    data-affiliation_detail_name="{{ op($row->affiliation_detail)->name ?? '' }}" data-school_year_name="{{ op($row->school_year)->name ?? '' }}" /></div>
                @else
                <div class="col-6 text-center"><x-icon name="fa-solid fa-trash-can" class="text-danger" style="font-size:22px;margin:0!important;cursor:pointer;" data-action="remove" data-id="{!! $row->id !!}"
                    data-first_name="{{ $row->first_name }}" data-last_name="{{ $row->last_name }}" data-affiliation_name="{{ op($row->affiliation)->name ?? '' }}"
                    data-affiliation_detail_name="{{ op($row->affiliation_detail)->name ?? '' }}" /></div>
                @endif
            </div>
        </td>
    </tr>
    @endforeach
</tbody>
<tfoot>
</tfoot>
</table> --}}
@endsection

{{-- 削除確認ダイアログ --}}
{{-- <x-confirm-dialog id="removeConfirm" type="confirm">
    <x-slot name="title">確認</x-slot>
    <h3 class="text-center mb-3">下記の利用者を退会させますが、<br>よろしいですか？</h3>
    <div class="d-flex justify-content-center">
        <table>
            <tbody>
                <tr>
                    <td>氏名&nbsp;</td><td data-field="removeFullName"></td>
                </tr>
                <tr>
                    <td>所属&nbsp;</td><td data-field="removeAffiliation"></td>
                </tr>
                <tr data-field="removeSchoolYearItem">
                    <td>学年&nbsp;</td><td data-field="removeSchoolYear"></td>
                </tr>
            </tbody>
        </table>
    </div>
    <br>
    <p class="text-center text-danger">※退会した場合、戻すことが出来ません。<br>&nbsp;&nbsp;戻す場合は、再登録が必要です。</p>
    <x-slot name="ok_button">はい</x-slot>
    <x-slot name="cancel_button">いいえ</x-slot>
</x-confirm-dialog> --}}
