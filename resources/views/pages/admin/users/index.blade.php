@extends('layouts.admin')

@use(Carbon\Carbon)
@use(App\AffiliationDetailTypes)
@use(App\Flags)
@use(App\Weekdays)
@use(App\SortTypes)

<x-script>
import { SCRSPage } from "/scrs-pages.js";
import { SCRSConfirmDialog } from "/dialogs/confirm-dialog.js";

class AdminUsersPage extends SCRSPage {

    #download = null;

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

    #show = null;
    #remove = null;

    #removeConfirmDialog = null;
    #removeFullName = null;
    #removeAffiliation = null;
    #removeSchoolYearItem = null;
    #removeSchoolYear = null;

    #rebuildSortOrders(columnName) {
        return [columnName].concat(this.#oldSortOrders.filter((sortOrder)=>(sortOrder!=columnName)));
    }

    constructor() {
        super();
        //
        this.#download = this.action("download", [ "click" ]);

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

        this.#show = this.actions("show", [ "click" ]);
        this.#remove = this.actions("remove", [ "click" ]);

        this.#removeConfirmDialog = new SCRSConfirmDialog(this, "removeConfirm", null, [ "ok" ]);
        this.#removeFullName = this.#removeConfirmDialog.field("removeFullName");
        this.#removeAffiliation = this.#removeConfirmDialog.field("removeAffiliation");
        this.#removeSchoolYearItem = this.#removeConfirmDialog.field("removeSchoolYearItem");
        this.#removeSchoolYear = this.#removeConfirmDialog.field("removeSchoolYear");
        {{-- this.#buyConfirmDialogTicketCount = this.#buyConfirmDialog.field("ticket_count", "name"); --}}
    }

    download_click(e) {
        this.get([ "/admin/users", "download" ]);
    }

    fullNameSortAsc_click(e) {
        this.#sortOrders.value = this.#rebuildSortOrders("full_name").join(",");
        this.#fullNameSort.value = SortTypes.ASC.id;
        this.get([ "/admin/users" ]);
    }

    fullNameSortDesc_click(e) {
        this.#sortOrders.value = this.#rebuildSortOrders("full_name").join(",");
        this.#fullNameSort.value = SortTypes.DESC.id;
        this.get([ "/admin/users" ]);
    }

    affiliationSortAsc_click(e) {
        this.#sortOrders.value = this.#rebuildSortOrders("affiliation").join(",");
        this.#affiliationSort.value = SortTypes.ASC.id;
        this.get([ "/admin/users" ]);
    }

    affiliationSortDesc_click(e) {
        this.#sortOrders.value = this.#rebuildSortOrders("affiliation").join(",");
        this.#affiliationSort.value = SortTypes.DESC.id;
        this.get([ "/admin/users" ]);
    }

    affiliationDetailSortAsc_click(e) {
        this.#sortOrders.value = this.#rebuildSortOrders("affiliation_detail").join(",");
        this.#affiliationDetailSort.value = SortTypes.ASC.id;
        this.get([ "/admin/users" ]);
    }

    affiliationDetailSortDesc_click(e) {
        this.#sortOrders.value = this.#rebuildSortOrders("affiliation_detail").join(",");
        this.#affiliationDetailSort.value = SortTypes.DESC.id;
        this.get([ "/admin/users" ]);
    }

    schoolYearSortAsc_click(e) {
        this.#sortOrders.value = this.#rebuildSortOrders("school_year").join(",");
        this.#schoolYearSort.value = SortTypes.ASC.id;
        this.get([ "/admin/users" ]);
    }

    schoolYearSortDesc_click(e) {
        this.#sortOrders = this.#rebuildSortOrders("school_year").join(",");
        this.#schoolYearSort.value = SortTypes.DESC.id;
        this.get([ "/admin/users" ]);
    }

    show_click(e) {
        const id = e.target.dataset['id'];
        this.forward([ "/admin/users", id ]);
    }

    remove_click(e) {
        const userId = e.target.dataset['id'];
        const lastName = e.target.dataset['last_name'];
        const firstName = e.target.dataset['first_name'];
        const affiliationName = e.target.dataset['affiliation_name'];
        const affiliationDetailName = e.target.dataset['affiliation_detail_name'];
        const schoolYearName = e.target.dataset['school_year_name'] || null;

        this.#removeFullName.text(lastName+'　'+firstName);
        this.#removeAffiliation.text(affiliationName+'　'+affiliationDetailName);
        if (schoolYearName) {
            this.#removeSchoolYearItem.removeClass("d-none");
            this.#removeSchoolYear.text(schoolYearName);
        }
        else {
            this.#removeSchoolYearItem.addClass("d-none");
            this.#removeSchoolYear.text('');
        }

        this.#removeConfirmDialog.open({ userId });
    }

    removeConfirm_ok(e) {
        //
        const { userId } = e.detail;
        this.delete([ "/admin/users", userId ]);
    }
}

SCRSPage.startup(()=>new AdminUsersPage());
</x-script>

@section('page.title')
<span>管理画面<span><x-icon name="fa-solid fa-angles-right" /><span>登録者一覧<span>
@endsection

@section('side-menu')
<x-virtical-menu id="idLeftSideMenu" category="left.side.menu" item-key="users" />
@endsection

@section('main')
<div class="row">
    <div class="col-8">

    </div>
    <div class="col-4 text-end">
        <button data-action="download" type="button" class="btn scrs-main-button" style="width:8em;">ダウンロード</button>
    </div>
</div>

<br>
<input type="hidden" data-field="fullNameSort" name="full_name_sort" value="{!! $full_name_sort ?? SortTypes::ASC !!}">
<input type="hidden" data-field="affiliationSort" name="affiliation_sort" value="{!! $affiliation_sort ?? SortTypes::ASC !!}">
<input type="hidden" data-field="affiliationDetailSort" name="affiliation_detail_sort" value="{!! $affiliation_detail_sort ?? SortTypes::ASC !!}">
<input type="hidden" data-field="schoolYearSort" name="school_year_sort" value="{!! $school_year_sort ?? SortTypes::ASC !!}">
<input type="hidden" data-field="sortOrders" name="sort_orders" value="{!! $sort_orders->join(',') !!}">

<table class="table table-bordered table-hover">
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
</table>
@endsection

{{-- 削除確認ダイアログ --}}
<x-confirm-dialog id="removeConfirm" type="confirm">
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
</x-confirm-dialog>
