@extends('layouts.default')

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
      <rb>山田</rb>
      <rt>やまだ</rt>
    </ruby>
    <ruby>
      <rb>花子</rb>
      <rt>はなこ</rt>
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
    <div class="col-3 text-end fs-3"><a href="#" data-action="previousMonth"><i class="fa-solid fa-angles-left text-body"></i></a></div>
    <div class="col-6 text-center fs-3">1/29～3/4</div>
    <div class="col-3 fs-3"><a href="#" data-action="nextMonth"><i class="fa-solid fa-angles-right text-body"></i></a></div>
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
        <tr>
            <td class="text-center align-middle py-1 text-secondary">1/29</td>
            <td class="text-center align-middle py-1 text-secondary">1/30</td>
            <td class="text-center align-middle py-1 text-secondary">1/31</td>
            <td class="bg-white text-center align-middle py-1">2/1</td>
            <td class="bg-white text-center align-middle py-1 ">2/2</td>
            <td class="bg-white text-center align-middle py-1">2/3</td>
            <td class="bg-white text-center align-middle py-1">2/4</td>
        </tr>
        <tr>
            <td class="bg-white text-center align-middle py-1">2/5</td>
            <td class="bg-white text-center align-middle py-1">2/6</td>
            <td class="bg-white text-center align-middle py-1">2/7</td>
            <td class="bg-white text-center align-middle py-1">2/8</td>
            <td class="bg-white text-center align-middle py-1">2/9</td>
            <td class="bg-white text-center align-middle py-1">2/10</td>
            <td class="bg-white text-center align-middle py-1">2/11</td>
        </tr>
        <tr>
            <td class="bg-white text-center align-middle py-1">2/12</td>
            <td class="bg-white text-center align-middle py-1">2/13</td>
            <td class="bg-white text-center align-middle py-1">2/14</td>
            <td class="bg-white text-center align-middle py-1">2/15</td>
            <td class="bg-white text-center align-middle py-1">2/16</td>
            <td class="bg-white text-center align-middle py-1">2/17</td>
            <td class="bg-white text-center align-middle py-1">2/18</td>
        </tr>
        <tr>
            <td class="bg-white text-center align-middle py-1">2/19</td>
            <td class="bg-white text-center align-middle py-1">2/20</td>
            <td class="bg-white text-center align-middle py-1">2/21</td>
            <td class="bg-white text-center align-middle py-1">2/22</td>
            <td class="bg-white text-center align-middle py-1">2/23</td>
            <td class="bg-white text-center align-middle py-1">2/24</td>
            <td class="bg-white text-center align-middle py-1">2/25</td>
        </tr>
        <tr>
            <td class="bg-white text-center align-middle py-1">2/26</td>
            <td class="bg-white text-center align-middle py-1"><b class="text-danger">2/27</b></td>
            <td class="bg-white text-center align-middle py-1">2/28</td>
            <td class="text-center align-middle py-1 text-secondary">3/1</td>
            <td class="text-center align-middle py-1 text-secondary">3/2</td>
            <td class="text-center align-middle py-1 text-secondary">3/3</td>
            <td class="text-center align-middle py-1 text-secondary">3/4</td>
        </tr>
    </tbody>
</table>

<br>

<div class="px-1 py-4">
    <h4>鶏モモ甘酢煮</h4>
    <div class="scrs-sheet-normal px-1 py-2 d-flex justify-content-center mb-3">
        <dl class="row m-0">
            <dt class="col-4 px-1">エネルギー(kcal)</dt>
            <dd class="col-2 text-end px-1">377.0</dd>
            <dt class="col-4 px-1">&nbsp;</dt>
            <dd class="col-2 text-end px-1">&nbsp;</dd>
            <dt class="col-4 px-1">炭水化物(g)</dt>
            <dd class="col-2 text-end px-1">13.4</dd>
            <dt class="col-4 px-1">たんぱく質(g)</dt>
            <dd class="col-2 text-end px-1">26.0</dd>
            <dt class="col-4 px-1">脂質(g)</dt>
            <dd class="col-2 text-end px-1">21.3</dd>
            <dt class="col-4 px-1">食物繊維(g)</dt>
            <dd class="col-2 text-end px-1">0.0</dd>
        </dl>
    </div>

    <h4>肉じゃが</h4>
    <div class="scrs-sheet-normal px-5 py-2 d-flex justify-content-center mb-3">
        <dl class="row m-0">
            <dt class="col-7">エネルギー(kcal)</dt>
            <dd class="col-5 text-end">58.5</dd>
            <dt class="col-7">炭水化物(g)</dt>
            <dd class="col-5 text-end">5.2</dd>
            <dt class="col-7">たんぱく質(g)</dt>
            <dd class="col-5 text-end">2.0</dd>
            <dt class="col-7">脂質(g)</dt>
            <dd class="col-5 text-end">3.6</dd>
            <dt class="col-7">食物繊維(g)</dt>
            <dd class="col-5 text-end">1.9</dd>
        </dl>
    </div>
</div>


{{-- <div class="px-5 py-4 scrs-sheet-ticket">
    <h3 class="text-center mb-2">回数券残数</h3>
    <div class="h2 text-center">
        <span class="text-nowrap">
            <span class="font-weight-normal">残り</span>
            <span class="px-2" style="font-size:200%;">00</span>
            <span class="font-weight-normal">回</span>
        </span>
    </div>
</div> --}}

{{-- <div class="d-flex justify-content-center">
    <button type="button" data-action="checkin" class="btn btn-lg scrs-main-button col-10 py-2">チェックインする</button>
</div> --}}
@endsection

{{-- <x-qr-code-reader-dialog id="qrCodeReader">
    <x-slot name="title">&nbsp;</x-slot>
</x-qr-code-reader-dialog> --}}
