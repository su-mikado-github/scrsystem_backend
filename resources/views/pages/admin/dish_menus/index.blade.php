@extends('layouts.admin')

@use(Carbon\Carbon)
@use(App\Weekdays)
@use(App\DishTypes)

<x-script>
import { SCRSPage } from "/scrs-pages.js";
import { SCRSUploadFileDialog } from "/dialogs/upload-file-dialog.js";

class AdminDishMenuPage extends SCRSPage {

    #uploadFileDialog = null;

    #upload = null;
    #download = null;

    #file_path = null;

    #modify = null;

    constructor() {
        super();
        //
        this.#uploadFileDialog = new SCRSUploadFileDialog(this, "uploadFile", null, [ "show", "hide", "ok", "error" ]);
        this.#upload = this.action("upload", [ "click" ]);
        this.#download = this.action("download", [ "click" ]);
        this.#file_path = this.field("file_path");
        this.#modify = this.actions("modify", [ "click" ]);
    }

    uploadFile_ok(e) {
        this.#uploadFileDialog.close();
    }

    upload_click(e) {
        this.#uploadFileDialog.open();
    }

    download_click(e) {
        this.forward([ "/admin/dish_menus", @json($dish_type->key), "download" ], { year: @json($month_calendar->year), month: @json($month_calendar->month) });
    }

    uploadFile_ok(e) {
        this.waitScreen(true);
        this.#file_path.value = e.detail.data?.path;
        this.#uploadFileDialog.close();
        this.post([ "/admin/dish_menus", @json($dish_type->key), "upload" ]);
    }

    uploadFile_error(e) {
        this.#uploadFileDialog.close();
    }

    modify_click(e) {
        const date = e.target.dataset["date"];
        this.forward([ "/admin/dish_menus", @json($dish_type->key), date ]);
    }
}

SCRSPage.startup(()=>new AdminDishMenuPage());
</x-script>

@section('page.title')
<span>管理画面<span>≫<span>メニュー編集<span>
@endsection

@section('side-menu')
<x-virtical-menu id="idLeftSideMenu" category="left.side.menu" item_key="dish_menus" />
@endsection

@section('main')
<div class="row">
    <div class="col-8">
        <div class="row">
            <div class="col-4 text-end">
                <a class="btn btn-link text-body" href="{!! url('/admin/dish_menus', [ $dish_type->key ]) !!}?year_month={!! Carbon::parse($month_calendar->previous_date)->format('Y-m') !!}">≪前月</a>
            </div>
            <div class="col-2 text-center py-2">
                <span class="text-nowrap3">{!! sprintf('%04d年%02d月', $month_calendar->year, $month_calendar->month) !!}</span>
            </div>
            <div class="col-2 text-center">
                <a class="btn btn-link text-body" href="{!! url('/admin/dish_menus', [ $dish_type->key ]) !!}">当月</a>
            </div>
            <div class="col-4 text-start">
                <a class="btn btn-link text-body" href="{!! url('/admin/dish_menus', [ $dish_type->key ]) !!}?year_month={!! Carbon::parse($month_calendar->next_date)->format('Y-m') !!}">次月≫</a>
            </div>
        </div>
    </div>
    <div class="col-4 text-end">
        <button data-action="upload" type="button" class="btn scrs-main-button" style="width:8em;">アップロード</button>
        <button data-action="download" type="button" class="btn scrs-sub-button" style="width:8em;">ダウンロード</button>
        <input data-field="file_path" type="hidden" name="file_path">
    </div>
</div>
<br>
<x-horizontal-tab id="idDishMenuTab" category="dish_menus.tab" item-key="{!! $dish_type->key !!}" query="{!! http_build_query([ 'year_month'=>sprintf('%02d-%02d', $month_calendar->year, $month_calendar->month) ]) !!}" />
<div class="bg-white p-3">
    <div class="table-responsive">
        <table class="table table-hover table-bordered">
        <colgroup>
            <col style="width:2em;">
            <col style="width:2em;">
            <col>
            <col style="width:7em;">
            <col style="width:6em;">
            <col style="width:6em;">
            <col style="width:6em;">
            <col style="width:6em;">
            <col style="width:2em;">
        </colgroup>
        <thead>
            <tr>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>料理メニュー</th>
                <th>エネルギー</th>
                <th>炭水化物</th>
                <th>タンパク質</th>
                <th>脂質</th>
                <th>食物遷移</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
        @foreach($calendars as $calendar)
        @eval($dish_menu = $calendar->daily_dish_menus->first() ?? null)
        <tr>
            <th class="text-end">{!! $calendar->day !!}</th>
            <th class="text-center">{!! Weekdays::of($calendar->weekday)->ja !!}</th>
            <td>@isset($dish_menu){{ (!$dish_menu ? ' ' : optional($dish_menu)->name) }}@else&nbsp;@endisset</td>
            <td class="text-end">@isset($dish_menu){!! number_format(optional($dish_menu)->energy, 1) !!}<span class="px-1">kcal</span>@else&nbsp;@endisset</td>
            <td class="text-end">@isset($dish_menu){!! number_format(optional($calendar->daily_dish_menus->first())->carbohydrates, 1) !!}<span class="px-1">g</span>@else&nbsp;@endisset</td>
            <td class="text-end">@isset($dish_menu){!! number_format(optional($calendar->daily_dish_menus->first())->protein, 1) !!}<span class="px-1">g</span>@else&nbsp;@endisset</td>
            <td class="text-end">@isset($dish_menu){!! number_format(optional($calendar->daily_dish_menus->first())->lipid, 1) !!}<span class="px-1">g</span>@else&nbsp;@endisset</td>
            <td class="text-end">@isset($dish_menu){!! number_format(optional($calendar->daily_dish_menus->first())->dietary_fiber, 1) !!}<span class="px-1">g</span>@else&nbsp;@endisset</td>
            <td class="text-center">
                @isset($dish_menu)
                <div class="d-flex flex-row">
                    <div class="col-6 text-center"><x-icon name="fa-solid fa-magnifying-glass" style="font-size:22px;margin:0!important;cursor:pointer;" data-action="modify" data-date="{!! $calendar->date->format('Y-m-d') !!}" /></div>
                </div>
                @endisset
            </td>
        </tr>
        @endforeach
        </tbody>
        </table>
    </div>
</div>
@endsection

<x-dialogs.upload-file id="uploadFile">
    <x-slot name="title">メニューCSVのアップロード</x-slot>
</x-dialogs.upload-file>

