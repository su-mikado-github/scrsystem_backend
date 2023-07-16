@extends('layouts.admin')

@use(Carbon\Carbon)
@use(App\AffiliationDetailTypes)
@use(App\Flags)
@use(App\Weekdays)
@use(App\SortTypes)

<x-script>
import { SCRSPage } from "/scrs-pages.js";
import { SCRSConfirmDialog } from "/dialogs/confirm-dialog.js";

class AdminTicketsPage extends SCRSPage {

    #payments = null;

    #paymentConfitmDialog = null;

    #oldSortOrders = @json($sort_orders->toArray())

    #datetimeSort = null;
    #fullNameSort = null;
    #affiliationSort = null;
    #affiliationDetailSort = null;
    #schoolYearSort = null;
    #sortOrders = null;

    #datetimeSortAsc = null;
    #datetimeSortDesc = null;
    #fullNameSortAsc = null;
    #fullNameSortDesc = null;
    #affiliationSortAsc = null;
    #affiliationSortDesc = null;
    #affiliationDetailSortAsc = null;
    #affiliationDetailSortDesc = null;
    #schoolYearSortAsc = null;
    #schoolYearSortDesc = null;

    #rebuildSortOrders(columnName) {
        return [columnName].concat(this.#oldSortOrders.filter((sortOrder)=>(sortOrder!=columnName)));
    }

    constructor() {
        super();

        this.#payments = this.actions("payment", [ "click" ]);

        this.#paymentConfitmDialog = new SCRSConfirmDialog(this, "payment", null, [ "ok" ]);

        this.#datetimeSort = this.field("datetimeSort");
        this.#fullNameSort = this.field("fullNameSort");
        this.#affiliationSort = this.field("affiliationSort");
        this.#affiliationDetailSort = this.field("affiliationDetailSort");
        this.#schoolYearSort = this.field("schoolYearSort");
        this.#sortOrders = this.field("sortOrders");

        this.#datetimeSortAsc = this.action("datetimeSortAsc", [ "click" ]);
        this.#datetimeSortDesc = this.action("datetimeSortDesc", [ "click" ]);
        this.#fullNameSortAsc = this.action("fullNameSortAsc", [ "click" ]);
        this.#fullNameSortDesc = this.action("fullNameSortDesc", [ "click" ]);
        this.#affiliationSortAsc = this.action("affiliationSortAsc", [ "click" ]);
        this.#affiliationSortDesc = this.action("affiliationSortDesc", [ "click" ]);
        this.#affiliationDetailSortAsc = this.action("affiliationDetailSortAsc", [ "click" ]);
        this.#affiliationDetailSortDesc = this.action("affiliationDetailSortDesc", [ "click" ]);
        this.#schoolYearSortAsc = this.action("schoolYearSortAsc", [ "click" ]);
        this.#schoolYearSortDesc = this.action("schoolYearSortDesc", [ "click" ]);
    }

    payment_click(e) {
        const buyTicketId = e.target.dataset.id;
        this.#paymentConfitmDialog.open({ buyTicketId });
    }

    payment_ok(e) {
        const params = e.detail;
        this.patch([ "/admin/tickets", params.buyTicketId, "payment" ]);
    }

    datetimeSortAsc_click(e) {
        this.#sortOrders.value = this.#rebuildSortOrders("datetime").join(",");
        this.#datetimeSort.value = SortTypes.ASC.id;
        this.get([ "/admin/tickets/years", @json($month_calendar->year), "months", @json($month_calendar->month) ]);
    }

    datetimeSortDesc_click(e) {
        this.#sortOrders.value = this.#rebuildSortOrders("datetime").join(",");
        this.#datetimeSort.value = SortTypes.DESC.id;
        this.get([ "/admin/tickets/years", @json($month_calendar->year), "months", @json($month_calendar->month) ]);
    }

    fullNameSortAsc_click(e) {
        this.#sortOrders.value = this.#rebuildSortOrders("full_name").join(",");
        this.#fullNameSort.value = SortTypes.ASC.id;
        this.get([ "/admin/tickets/years", @json($month_calendar->year), "months", @json($month_calendar->month) ]);
    }

    fullNameSortDesc_click(e) {
        this.#sortOrders.value = this.#rebuildSortOrders("full_name").join(",");
        this.#fullNameSort.value = SortTypes.DESC.id;
        this.get([ "/admin/tickets/years", @json($month_calendar->year), "months", @json($month_calendar->month) ]);
    }

    affiliationSortAsc_click(e) {
        this.#sortOrders.value = this.#rebuildSortOrders("affiliation").join(",");
        this.#affiliationSort.value = SortTypes.ASC.id;
        this.get([ "/admin/tickets/years", @json($month_calendar->year), "months", @json($month_calendar->month) ]);
    }

    affiliationSortDesc_click(e) {
        this.#sortOrders.value = this.#rebuildSortOrders("affiliation").join(",");
        this.#affiliationSort.value = SortTypes.DESC.id;
        this.get([ "/admin/tickets/years", @json($month_calendar->year), "months", @json($month_calendar->month) ]);
    }

    affiliationDetailSortAsc_click(e) {
        this.#sortOrders.value = this.#rebuildSortOrders("affiliation_detail").join(",");
        this.#affiliationDetailSort.value = SortTypes.ASC.id;
        this.get([ "/admin/tickets/years", @json($month_calendar->year), "months", @json($month_calendar->month) ]);
    }

    affiliationDetailSortDesc_click(e) {
        this.#sortOrders.value = this.#rebuildSortOrders("affiliation_detail").join(",");
        this.#affiliationDetailSort.value = SortTypes.DESC.id;
        this.get([ "/admin/tickets/years", @json($month_calendar->year), "months", @json($month_calendar->month) ]);
    }

    schoolYearSortAsc_click(e) {
        this.#sortOrders.value = this.#rebuildSortOrders("school_year").join(",");
        this.#schoolYearSort.value = SortTypes.ASC.id;
        this.get([ "/admin/tickets/years", @json($month_calendar->year), "months", @json($month_calendar->month) ]);
    }

    schoolYearSortDesc_click(e) {
        this.#sortOrders = this.#rebuildSortOrders("school_year").join(",");
        this.#schoolYearSort.value = SortTypes.DESC.id;
        this.get([ "/admin/tickets/years", @json($month_calendar->year), "months", @json($month_calendar->month) ]);
    }
}

SCRSPage.startup(()=>new AdminTicketsPage());
</x-script>

@section('page.title')
<span>管理画面<span><x-icon name="fa-solid fa-angles-right" /><span>食券購入一覧<span>
@endsection

@section('side-menu')
<x-virtical-menu id="idLeftSideMenu" category="left.side.menu" item-key="tickets" />
@endsection

@section('main')
<input type="hidden" data-field="datetimeSort" name="datetime_sort" value="{!! $datetime_sort ?? SortTypes::ASC !!}">
<input type="hidden" data-field="fullNameSort" name="full_name_sort" value="{!! $full_name_sort ?? SortTypes::ASC !!}">
<input type="hidden" data-field="affiliationSort" name="affiliation_sort" value="{!! $affiliation_sort ?? SortTypes::ASC !!}">
<input type="hidden" data-field="affiliationDetailSort" name="affiliation_detail_sort" value="{!! $affiliation_detail_sort ?? SortTypes::ASC !!}">
<input type="hidden" data-field="schoolYearSort" name="school_year_sort" value="{!! $school_year_sort ?? SortTypes::ASC !!}">
<input type="hidden" data-field="sortOrders" name="sort_orders" value="{!! $sort_orders->join(',') !!}">

<div class="row">
    <div class="col-8">
        <div class="row">
            <div class="col-4 text-end">
                <a class="btn btn-link text-body" href="{!! route('admin.tickets.year_month', [ 'year'=>$month_calendar->previous_year, 'month'=>$month_calendar->previous_month ]) !!}">≪前月</a>
            </div>
            <div class="col-2 text-center py-2">
                <span class="text-nowrap3">{!! sprintf('%04d年%02d月', $month_calendar->year, $month_calendar->month) !!}</span>
            </div>
            <div class="col-2 text-center">
                <a class="btn btn-link text-body" href="{!! route('admin.tickets') !!}">当月</a>
            </div>
            <div class="col-4 text-start">
                <a class="btn btn-link text-body" href="{!! route('admin.tickets.year_month', [ 'year'=>$month_calendar->next_year, 'month'=>$month_calendar->next_month ]) !!}">次月≫</a>
            </div>
        </div>
    </div>
    <div class="col-4 text-end">
    </div>
</div>

<br>
<div class="col-10">
    <table class="table table-bordered table-hover">
    <colgroup>
        <col style="width:10em;"> {{-- 日時 --}}
        <col style="width:12em;"> {{-- 氏名 --}}
        <col> {{-- 所属 --}}
        <col style="width:8em;"> {{-- 学年 --}}
        <col style="width:6em;"> {{-- 購入枚数 --}}
        <col style="width:10em;"> {{-- 支払日時 --}}
        <col style="width:4em;"> {{-- 支払 --}}
    </colgroup>
    <thead class="scrs-bg-main">
        <tr>
            <th>
                <div class="d-flex">
                    <div class="flex-grow-1">日時</div>
                    <div>
                        <img src="/images/icons/sort_white.svg" style="height:24px;" usemap="#datetime_sort_map">
                        <map name="datetime_sort_map"><area shape="rect" coords="0,0,24,12" data-action="datetimeSortAsc"><area shape="rect" coords="0,12,24,24" data-action="datetimeSortDesc"></map>
                    </div>
                </div>
            </th>
            <th>
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
            <th>
                <div class="d-flex">
                    <div class="flex-grow-1">学年</div>
                    <div>
                        <img src="/images/icons/sort_white.svg" style="height:24px;" usemap="#school_year_sort_map">
                        <map name="school_year_sort_map"><area shape="rect" coords="0,0,24,12" data-action="schoolYearSortAsc"><area shape="rect" coords="0,12,24,24" data-action="schoolYearSortDesc"></map>
                    </div>
                </div>
            </th>
            <th>購入枚数</th>
            <th>支払日時</th>
            <th>支払</th>
        </tr>
    </thead>
    <tbody>
        @foreach($buy_tickets as $buy_ticket)
        @eval($user = $buy_ticket->user)
        <tr class="bg-white">
            <td class="text-center">{{ $buy_ticket->buy_dt->format('Y/m/d H:i') }}</td>
            <td>{{ $user->last_name }} {{ $user->first_name }}</td>
            <td>
                <div class="d-flex flex-row">
                    <div class="col-6">{{ $user->affiliation->name }}</div>
                    <div class="col-6">{{ $user->affiliation_detail->name }}</div>
                </div>
            </td>
            <td>@if($user->affiliation->detail_type == AffiliationDetailTypes::INTERNAL)<span>{{ $user->school_year->name }}</span>@else &nbsp; @endif</td>
            <td class="text-end">{{ number_format($buy_ticket->ticket_count) }}枚</td>
            <td class="text-center">@isset($buy_ticket->payment_dt){{ $buy_ticket->payment_dt->format('Y/m/d H:i') }}@else 未払い @endif</td>
            <td class="text-center">@isset($buy_ticket->payment_dt) 済 @else<x-icon name="fa-solid fa-cash-register" class="text-body" style="font-size:22px;margin:0!important;cursor:pointer;" data-action="payment" data-id="{!! $buy_ticket->id !!}" />@endif</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
    </tfoot>
    </table>
</div>
@endsection

{{--  --}}
<x-confirm-dialog id="payment" type="confirm">
    <x-slot name="title">確認</x-slot>
    <h3 data-field="message" class="text-center mb-3">支払い完了でよろしいですか？</h3>
    <br>
    <x-slot name="ok_button">支払い完了</x-slot>
    <x-slot name="cancel_button">いいえ</x-slot>
</x-confirm-dialog>
