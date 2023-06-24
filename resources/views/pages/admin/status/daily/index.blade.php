@extends('layouts.admin')

@use(Carbon\Carbon)
@use(App\AffiliationDetailTypes)
@use(App\Flags)
@use(App\ReserveTypes)
@use(App\SortTypes)
@use(App\Weekdays)

<x-script>
import { SCRSPage } from "/scrs-pages.js";

class AdminStatusDailyPage extends SCRSPage {

    #date = @json($date);

    #oldSortOrders = @json($sort_orders->toArray())

    #fullNameSort = null;
    #affiliationSort = null;
    #affiliationDetailSort = null;
    #schoolYearSort = null;
    #sortOrders = null;

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
        //

        this.#fullNameSort = this.field("fullNameSort");
        this.#affiliationSort = this.field("affiliationSort");
        this.#affiliationDetailSort = this.field("affiliationDetailSort");
        this.#schoolYearSort = this.field("schoolYearSort");
        this.#sortOrders = this.field("sortOrders");

        this.#fullNameSortAsc = this.action("fullNameSortAsc", [ "click" ]);
        this.#fullNameSortDesc = this.action("fullNameSortDesc", [ "click" ]);
        this.#affiliationSortAsc = this.action("affiliationSortAsc", [ "click" ]);
        this.#affiliationSortDesc = this.action("affiliationSortDesc", [ "click" ]);
        this.#affiliationDetailSortAsc = this.action("affiliationDetailSortAsc", [ "click" ]);
        this.#affiliationDetailSortDesc = this.action("affiliationDetailSortDesc", [ "click" ]);
        this.#schoolYearSortAsc = this.action("schoolYearSortAsc", [ "click" ]);
        this.#schoolYearSortDesc = this.action("schoolYearSortDesc", [ "click" ]);
    }

    fullNameSortAsc_click(e) {
        this.#sortOrders.value = this.#rebuildSortOrders("full_name").join(",");
        this.#fullNameSort.value = SortTypes.ASC.id;
        this.get([ "/admin/status/daily", this.#date ]);
    }

    fullNameSortDesc_click(e) {
        this.#sortOrders.value = this.#rebuildSortOrders("full_name").join(",");
        this.#fullNameSort.value = SortTypes.DESC.id;
        this.get([ "/admin/status/daily", this.#date ]);
    }

    affiliationSortAsc_click(e) {
        this.#sortOrders.value = this.#rebuildSortOrders("affiliation").join(",");
        this.#affiliationSort.value = SortTypes.ASC.id;
        this.get([ "/admin/status/daily", this.#date ]);
    }

    affiliationSortDesc_click(e) {
        this.#sortOrders.value = this.#rebuildSortOrders("affiliation").join(",");
        this.#affiliationSort.value = SortTypes.DESC.id;
        this.get([ "/admin/status/daily", this.#date ]);
    }

    affiliationDetailSortAsc_click(e) {
        this.#sortOrders.value = this.#rebuildSortOrders("affiliation_detail").join(",");
        this.#affiliationDetailSort.value = SortTypes.ASC.id;
        this.get([ "/admin/status/daily", this.#date ]);
    }

    affiliationDetailSortDesc_click(e) {
        this.#sortOrders.value = this.#rebuildSortOrders("affiliation_detail").join(",");
        this.#affiliationDetailSort.value = SortTypes.DESC.id;
        this.get([ "/admin/status/daily", this.#date ]);
    }

    schoolYearSortAsc_click(e) {
        this.#sortOrders.value = this.#rebuildSortOrders("school_year").join(",");
        this.#schoolYearSort.value = SortTypes.ASC.id;
        this.get([ "/admin/status/daily", this.#date ]);
    }

    schoolYearSortDesc_click(e) {
        this.#sortOrders = this.#rebuildSortOrders("school_year").join(",");
        this.#schoolYearSort.value = SortTypes.DESC.id;
        this.get([ "/admin/status/daily", this.#date ]);
    }
}

SCRSPage.startup(()=>new AdminStatusDailyPage());
</x-script>

@section('page.title')
<span>管理画面<span><x-icon name="fa-solid fa-angles-right" /><span>利用状況<span><x-icon name="fa-solid fa-angles-right" /><span>詳細</span>
@endsection

@section('side-menu')
<x-virtical-menu id="idLeftSideMenu" category="left.side.menu" item-key="status" />
@endsection

@section('main')
<div class="row">
    <div class="col-4">
        <a class="btn btn-link text-body" href="{!! route('admin.status') !!}?year_month={!! $calendar->date->format('Y-m') !!}">≪利用状況に戻る</a>
    </div>
    <div class="col-8 text-center">
        <div class="row">
            <div class="col-4 text-end">
                <a class="btn btn-link text-body" href="{!! route('admin.status.daily', [ 'date'=>$calendar->previous_date->format('Y-m-d') ]) !!}">≪前日</a>
            </div>
            <div class="col-2 text-center py-2">
                <span class="text-nowrap3">{!! $calendar->date->format('Y年m月d日') !!}({!! Weekdays::fromDate($calendar->date)->ja !!})</span>
            </div>
            <div class="col-2 text-center">
                <a class="btn btn-link text-body" href="{!! route('admin.status.daily') !!}">本日</a>
            </div>
            <div class="col-4 text-start">
                <a class="btn btn-link text-body" href="{!! route('admin.status.daily', [ 'date'=>$calendar->next_date->format('Y-m-d') ]) !!}">翌日≫</a>
            </div>
        </div>
    </div>
</div>

<br>
<input type="hidden" data-field="fullNameSort" name="full_name_sort" value="{!! $full_name_sort ?? SortTypes::ASC !!}">
<input type="hidden" data-field="affiliationSort" name="affiliation_sort" value="{!! $affiliation_sort ?? SortTypes::ASC !!}">
<input type="hidden" data-field="affiliationDetailSort" name="affiliation_detail_sort" value="{!! $affiliation_detail_sort ?? SortTypes::ASC !!}">
<input type="hidden" data-field="schoolYearSort" name="school_year_sort" value="{!! $school_year_sort ?? SortTypes::ASC !!}">
<input type="hidden" data-field="sortOrders" name="sort_orders" value="{!! $sort_orders->join(',') !!}">

<table class="table table-bordered table-hover" style="width:calc( 100% * 8 / 12 );">
<thead class="scrs-bg-main">
    <tr>
        <th style="width:10em;" rowspan="2">
            <div class="d-flex">
                <div class="flex-grow-1">氏名</div>
                <div>
                    <img src="/images/icons/sort_white.svg" style="height:24px;" usemap="#full_name_sort_map">
                    <map name="full_name_sort_map"><area shape="rect" coords="0,0,24,12" data-action="fullNameSortAsc"><area shape="rect" coords="0,12,24,24" data-action="fullNameSortDesc"></map>
                </div>
            </div>
        </th>
        <th rowspan="2">
            <div class="d-flex flex-row">
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
            </div>
        </th>
        <th style="width:6em;" rowspan="2">
            <div class="d-flex">
                <div class="flex-grow-1">学年</div>
                <div>
                    <img src="/images/icons/sort_white.svg" style="height:24px;" usemap="#school_year_sort_map">
                    <map name="school_year_sort_map"><area shape="rect" coords="0,0,24,12" data-action="schoolYearSortAsc"><area shape="rect" coords="0,12,24,24" data-action="schoolYearSortDesc"></map>
                </div>
            </div>
        </th>
        <th colspan="2">食堂</th>
        <th colspan="2">弁当</th>
    </tr>
    <tr>
        <th style="width:6em;">予約時間</th>
        <th style="width:6em;">状態</th>
        <th style="width:6em;">予約時間</th>
        <th style="width:6em;">様態</th>
    </tr>
</thead>
<tbody>
    @php
        function time2hhmm($time) {
            if (isset($time)) {
                list($h, $m, $s) = explode(':', $time);
                return sprintf('%02d:%02d', $h, $m);
            }
            return null;
        }
    @endphp
    @foreach($users as $row)
    @php
        $reserve = $row->reserves()->dateBy($calendar->date)->first();
        $visit = transform($reserve, function($reserve) {
            $result = [ 'state'=>null, 'time'=>null ];
            if (in_array($reserve->type, [ ReserveTypes::VISIT_SOCCER, ReserveTypes::VISIT_NO_SOCCER ])) {
                if (isset($reserve->cancel_dt)) {
                    $result['state'] = '取消';
                }
                else if (isset($reserve->checkin_dt)) {
                    $result['state'] = '来店済';
                }
                else {
                    $result['state'] = '予約中';
                }
                $result['time'] = time2hhmm($reserve->time);
            }
            return $result;
        });
        $lunchbox = transform($reserve, function($reserve) {
            $result = [ 'state'=>null, 'time'=>null ];
            if (in_array($reserve->type, [ ReserveTypes::LUNCHBOX ])) {
                if (isset($reserve->cancel_dt)) {
                    $result['state'] = '取消';
                }
                else if (isset($reserve->checkin_dt)) {
                    return '受取済';
                }
                else {
                    $result['state'] = '予約中';
                }
                $result['time'] = time2hhmm($reserve->time);
            }
            return $result;
        });
    @endphp
    <tr>
        <td>{{ $row->last_name }} {{ $row->first_name }}</td>
        <td>
            <div class="d-flex flex-row">
                <div class="col-6 text-start">{{ $row->affiliation->name }}</div>
                <div class="col-6 text-start">{{ $row->affiliation_detail->name }}</div>
            </div>
        </td>
        <td class="text-center">@if($row->affiliation->detail_type==AffiliationDetailTypes::INTERNAL){{ $row->school_year->name }}@else &nbsp; @endif</td>
        <td class="text-center">{{ $visit['time'] ?? ' ' }}</td>
        <td class="text-center">@isset($reserve){{ $visit['state'] ?? ' ' }}@else &nbsp; @endisset</td>
        <td class="text-center">@isset($reserve){{ $lunchbox['time'] ?? ' ' }}@else &nbsp; @endisset</td>
        <td class="text-center">@isset($reserve){{ $lunchbox['state'] ?? ' ' }}@else &nbsp; @endisset</td>
    </tr>
    @endforeach
</tbody>
<tfoot>
</tfoot>
</table>
@endsection
