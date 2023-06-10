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

    constructor() {
        super();
        //
        this.#uploadFileDialog = new SCRSUploadFileDialog(this, "uploadFile", null, [ "show", "hide", "ok", "error" ]);
        this.#upload = this.action("upload", [ "click" ]);
        this.#download = this.action("download", [ "click" ]);
        this.#file_path = this.field("file_path");
    }

    uploadFile_ok(e) {
        this.#uploadFileDialog.close();
    }

    upload_click(e) {
        this.#uploadFileDialog.open();
    }

    download_click(e) {
        alert("ファイル・ダウンロード");
    }

    uploadFile_ok(e) {
        this.#file_path.value = e.detail.data?.path;
        this.#uploadFileDialog.close();
        this.submit("POST", "/admin/dish_menu/{!! $dish_type_key !!}/upload");
    }

    uploadFile_error(e) {
        this.#uploadFileDialog.close();
    }
}

SCRSPage.startup(()=>new AdminDishMenuPage());
</x-script>

@section('page.title')
<span>管理画面<span>≫<span>メニュー編集<span>
@endsection

@section('side-menu')
<x-virtical-menu id="idLeftSideMenu" category="left.side.menu" item_key="dish_menu" />
@endsection

@section('main')
<div class="row">
    <div class="col-8">
        <div class="row">
            <div class="col-4 text-end">
                <a class="btn btn-link text-body" href="{!! url('/admin/dish_menu', [ $dish_type_key ]) !!}?year_month={!! Carbon::parse($month_calendar->previous_date)->format('Y-m') !!}">≪前月</a>
            </div>
            <div class="col-2 text-center py-2">
                <span class="text-nowrap3">{!! sprintf('%04d年%02d月', $month_calendar->year, $month_calendar->month) !!}</span>
            </div>
            <div class="col-2 text-center">
                <a class="btn btn-link text-body" href="{!! url('/admin/dish_menu', [ $dish_type_key ]) !!}">当月</a>
            </div>
            <div class="col-4 text-start">
                <a class="btn btn-link text-body" href="{!! url('/admin/dish_menu', [ $dish_type_key ]) !!}?year_month={!! Carbon::parse($month_calendar->next_date)->format('Y-m') !!}">次月≫</a>
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
<x-horizontal-tab id="idDishMenuTab" category="dish_menu.tab" item-key="{!! $dish_type_key !!}" />
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
            <td class="text-center">&nbsp;</td>
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

