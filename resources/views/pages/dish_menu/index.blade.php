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

{{-- 食堂メニュー --}}
<div class="card">
    <div class="card-header p-1">
        <h5 class="mb-0">食堂メニュー</h5>
    </div>
    <div class="card-body p-1">
        <h6 class="mb-0">鶏モモ甘酢煮</h6>
        <div class="scrs-sheet-normal ps-4 pe-2 py-2 d-flex justify-content-center mb-2">
            <table class="col-12 col-sm-11 col-md-10 col-lg-8 col-xl-6">
            <tbody>
            <tr>
                <th class="">エネルギー</th>
                <td class="text-nowrap text-end">377.0<b class="d-inline-block" style="font-size:80%;width:2em;">kcal</b></td>
                <th class=""></th>
                <td class="text-nowrap text-end"></td>
            </tr>
            <tr>
                <th class="">炭水化物</th>
                <td class="text-nowrap text-end">13.4<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">たんぱく質</th>
                <td class="text-nowrap text-end">26.0<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            <tr>
                <th class="">脂質</th>
                <td class="text-nowrap text-end">21.3<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">食物繊維</th>
                <td class="text-nowrap text-end">0.0<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            </tbody>
            </table>
        </div>

        <h6 class="mb-0">肉じゃが</h6>
        <div class="scrs-sheet-normal ps-4 pe-2 py-2 d-flex justify-content-center mb-2">
            <table class="col-12 col-sm-11 col-md-10 col-lg-8 col-xl-6">
            <tbody>
            <tr>
                <th class="">エネルギー</th>
                <td class="text-nowrap text-end">58.5<b class="d-inline-block" style="font-size:80%;width:2em;">kcal</b></td>
                <th class=""></th>
                <td class="text-nowrap text-end"></td>
            </tr>
            <tr>
                <th class="">炭水化物</th>
                <td class="text-nowrap text-end">5.2<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">たんぱく質</th>
                <td class="text-nowrap text-end">2.0<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            <tr>
                <th class="">脂質</th>
                <td class="text-nowrap text-end">3.6<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">食物繊維</th>
                <td class="text-nowrap text-end">1.9<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            </tbody>
            </table>
        </div>

        <h6 class="mb-0">春雨サラダ</h6>
        <div class="scrs-sheet-normal ps-4 pe-2 py-2 d-flex justify-content-center mb-2">
            <table class="col-12 col-sm-11 col-md-10 col-lg-8 col-xl-6">
            <tbody>
            <tr>
                <th class="">エネルギー</th>
                <td class="text-nowrap text-end">69.8<b class="d-inline-block" style="font-size:80%;width:2em;">kcal</b></td>
                <th class=""></th>
                <td class="text-nowrap text-end"></td>
            </tr>
            <tr>
                <th class="">炭水化物</th>
                <td class="text-nowrap text-end">7.7<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">たんぱく質</th>
                <td class="text-nowrap text-end">2.8<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            <tr>
                <th class="">脂質</th>
                <td class="text-nowrap text-end">3.6<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">食物繊維</th>
                <td class="text-nowrap text-end">0.6<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            </tbody>
            </table>
        </div>

        <h6 class="mb-0">ぬたみそ</h6>
        <div class="scrs-sheet-normal ps-4 pe-2 py-2 d-flex justify-content-center mb-2">
            <table class="col-12 col-sm-11 col-md-10 col-lg-8 col-xl-6">
            <tbody>
            <tr>
                <th class="">エネルギー</th>
                <td class="text-nowrap text-end">34.4<b class="d-inline-block" style="font-size:80%;width:2em;">kcal</b></td>
                <th class=""></th>
                <td class="text-nowrap text-end"></td>
            </tr>
            <tr>
                <th class="">炭水化物</th>
                <td class="text-nowrap text-end">7.7<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">たんぱく質</th>
                <td class="text-nowrap text-end">0.8<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            <tr>
                <th class="">脂質</th>
                <td class="text-nowrap text-end">0.2<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">食物繊維</th>
                <td class="text-nowrap text-end">0.8<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            </tbody>
            </table>
        </div>

        <h6 class="mb-0">味噌汁</h6>
        <div class="scrs-sheet-normal ps-4 pe-2 py-2 d-flex justify-content-center mb-2">
            <table class="col-12 col-sm-11 col-md-10 col-lg-8 col-xl-6">
            <tbody>
            <tr>
                <th class="">エネルギー</th>
                <td class="text-nowrap text-end">44.5<b class="d-inline-block" style="font-size:80%;width:2em;">kcal</b></td>
                <th class=""></th>
                <td class="text-nowrap text-end"></td>
            </tr>
            <tr>
                <th class="">炭水化物</th>
                <td class="text-nowrap text-end">4.0<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">たんぱく質</th>
                <td class="text-nowrap text-end">6.3<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            <tr>
                <th class="">脂質</th>
                <td class="text-nowrap text-end">3.8<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">食物繊維</th>
                <td class="text-nowrap text-end">2.2<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            </tbody>
            </table>
        </div>

        <h6 class="mb-0">白米</h6>
        <div class="scrs-sheet-normal ps-4 pe-2 py-2 d-flex justify-content-center mb-2">
            <table class="col-12 col-sm-11 col-md-10 col-lg-8 col-xl-6">
            <tbody>
            <tr>
                <th class="">エネルギー</th>
                <td class="text-nowrap text-end">339.0<b class="d-inline-block" style="font-size:80%;width:2em;">kcal</b></td>
                <th class=""></th>
                <td class="text-nowrap text-end"></td>
            </tr>
            <tr>
                <th class="">炭水化物</th>
                <td class="text-nowrap text-end">79.2<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">たんぱく質</th>
                <td class="text-nowrap text-end">5.4<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            <tr>
                <th class="">脂質</th>
                <td class="text-nowrap text-end">0.9<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">食物繊維</th>
                <td class="text-nowrap text-end">3.3<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            </tbody>
            </table>
        </div>

        <h6 class="mb-0">サラダ</h6>
        <div class="scrs-sheet-normal ps-4 pe-2 py-2 d-flex justify-content-center mb-2">
            <table class="col-12 col-sm-11 col-md-10 col-lg-8 col-xl-6">
            <tbody>
            <tr>
                <th class="">エネルギー</th>
                <td class="text-nowrap text-end">5.9<b class="d-inline-block" style="font-size:80%;width:2em;">kcal</b></td>
                <th class=""></th>
                <td class="text-nowrap text-end"></td>
            </tr>
            <tr>
                <th class="">炭水化物</th>
                <td class="text-nowrap text-end">5.9<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">たんぱく質</th>
                <td class="text-nowrap text-end">2.6<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            <tr>
                <th class="">脂質</th>
                <td class="text-nowrap text-end">1.0<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">食物繊維</th>
                <td class="text-nowrap text-end">2.4<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            </tbody>
            </table>
        </div>

        <h6 class="mb-0">糠付け</h6>
        <div class="scrs-sheet-normal ps-4 pe-2 py-2 d-flex justify-content-center mb-2">
            <table class="col-12 col-sm-11 col-md-10 col-lg-8 col-xl-6">
            <tbody>
            <tr>
                <th class="">エネルギー</th>
                <td class="text-nowrap text-end">3.2<b class="d-inline-block" style="font-size:80%;width:2em;">kcal</b></td>
                <th class=""></th>
                <td class="text-nowrap text-end"></td>
            </tr>
            <tr>
                <th class="">炭水化物</th>
                <td class="text-nowrap text-end">3.2<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">たんぱく質</th>
                <td class="text-nowrap text-end">0.4<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            <tr>
                <th class="">脂質</th>
                <td class="text-nowrap text-end">0.1<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">食物繊維</th>
                <td class="text-nowrap text-end">2.0<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-transparent p-1">
        <h6 class="mb-0">total</h6>
        <div class="scrs-sheet-normal ps-4 pe-2 py-2 d-flex justify-content-center mb-2">
            <table class="col-12 col-sm-11 col-md-10 col-lg-8 col-xl-6">
            <tbody>
            <tr>
                <th class="">エネルギー</th>
                <td class="text-nowrap text-end">932.2<b class="d-inline-block" style="font-size:80%;width:2em;">kcal</b></td>
                <th class=""></th>
                <td class="text-nowrap text-end"></td>
            </tr>
            <tr>
                <th class="">炭水化物</th>
                <td class="text-nowrap text-end">126.2<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">たんぱく質</th>
                <td class="text-nowrap text-end">46.4<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            <tr>
                <th class="">脂質</th>
                <td class="text-nowrap text-end">32.6<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">食物繊維</th>
                <td class="text-nowrap text-end">13.3<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            </tbody>
            </table>
        </div>

    </div>
</div>

<br>

{{-- お弁当 --}}
<div class="card">
    <div class="card-header p-1">
        <h5 class="mb-0">お弁当</h5>
    </div>
    <div class="card-body p-1">
        <h6 class="mb-0">鶏モモ甘酢煮</h6>
        <div class="scrs-sheet-normal ps-4 pe-2 py-2 d-flex justify-content-center mb-2">
            <table class="col-12 col-sm-11 col-md-10 col-lg-8 col-xl-6">
            <tbody>
            <tr>
                <th class="">エネルギー</th>
                <td class="text-nowrap text-end">377.0<b class="d-inline-block" style="font-size:80%;width:2em;">kcal</b></td>
                <th class=""></th>
                <td class="text-nowrap text-end"></td>
            </tr>
            <tr>
                <th class="">炭水化物</th>
                <td class="text-nowrap text-end">13.4<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">たんぱく質</th>
                <td class="text-nowrap text-end">26.0<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            <tr>
                <th class="">脂質</th>
                <td class="text-nowrap text-end">21.3<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">食物繊維</th>
                <td class="text-nowrap text-end">0.0<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            </tbody>
            </table>
        </div>

        <h6 class="mb-0">肉じゃが</h6>
        <div class="scrs-sheet-normal ps-4 pe-2 py-2 d-flex justify-content-center mb-2">
            <table class="col-12 col-sm-11 col-md-10 col-lg-8 col-xl-6">
            <tbody>
            <tr>
                <th class="">エネルギー</th>
                <td class="text-nowrap text-end">58.5<b class="d-inline-block" style="font-size:80%;width:2em;">kcal</b></td>
                <th class=""></th>
                <td class="text-nowrap text-end"></td>
            </tr>
            <tr>
                <th class="">炭水化物</th>
                <td class="text-nowrap text-end">5.2<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">たんぱく質</th>
                <td class="text-nowrap text-end">2.0<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            <tr>
                <th class="">脂質</th>
                <td class="text-nowrap text-end">3.6<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">食物繊維</th>
                <td class="text-nowrap text-end">1.9<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            </tbody>
            </table>
        </div>

        <h6 class="mb-0">春雨サラダ</h6>
        <div class="scrs-sheet-normal ps-4 pe-2 py-2 d-flex justify-content-center mb-2">
            <table class="col-12 col-sm-11 col-md-10 col-lg-8 col-xl-6">
            <tbody>
            <tr>
                <th class="">エネルギー</th>
                <td class="text-nowrap text-end">69.8<b class="d-inline-block" style="font-size:80%;width:2em;">kcal</b></td>
                <th class=""></th>
                <td class="text-nowrap text-end"></td>
            </tr>
            <tr>
                <th class="">炭水化物</th>
                <td class="text-nowrap text-end">7.7<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">たんぱく質</th>
                <td class="text-nowrap text-end">2.8<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            <tr>
                <th class="">脂質</th>
                <td class="text-nowrap text-end">3.6<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">食物繊維</th>
                <td class="text-nowrap text-end">0.6<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            </tbody>
            </table>
        </div>

        <h6 class="mb-0">ぬたみそ</h6>
        <div class="scrs-sheet-normal ps-4 pe-2 py-2 d-flex justify-content-center mb-2">
            <table class="col-12 col-sm-11 col-md-10 col-lg-8 col-xl-6">
            <tbody>
            <tr>
                <th class="">エネルギー</th>
                <td class="text-nowrap text-end">34.4<b class="d-inline-block" style="font-size:80%;width:2em;">kcal</b></td>
                <th class=""></th>
                <td class="text-nowrap text-end"></td>
            </tr>
            <tr>
                <th class="">炭水化物</th>
                <td class="text-nowrap text-end">7.7<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">たんぱく質</th>
                <td class="text-nowrap text-end">0.8<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            <tr>
                <th class="">脂質</th>
                <td class="text-nowrap text-end">0.2<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">食物繊維</th>
                <td class="text-nowrap text-end">0.8<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            </tbody>
            </table>
        </div>

        <h6 class="mb-0">味噌汁</h6>
        <div class="scrs-sheet-normal ps-4 pe-2 py-2 d-flex justify-content-center mb-2">
            <table class="col-12 col-sm-11 col-md-10 col-lg-8 col-xl-6">
            <tbody>
            <tr>
                <th class="">エネルギー</th>
                <td class="text-nowrap text-end">44.5<b class="d-inline-block" style="font-size:80%;width:2em;">kcal</b></td>
                <th class=""></th>
                <td class="text-nowrap text-end"></td>
            </tr>
            <tr>
                <th class="">炭水化物</th>
                <td class="text-nowrap text-end">4.0<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">たんぱく質</th>
                <td class="text-nowrap text-end">6.3<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            <tr>
                <th class="">脂質</th>
                <td class="text-nowrap text-end">3.8<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">食物繊維</th>
                <td class="text-nowrap text-end">2.2<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            </tbody>
            </table>
        </div>

        <h6 class="mb-0">白米</h6>
        <div class="scrs-sheet-normal ps-4 pe-2 py-2 d-flex justify-content-center mb-2">
            <table class="col-12 col-sm-11 col-md-10 col-lg-8 col-xl-6">
            <tbody>
            <tr>
                <th class="">エネルギー</th>
                <td class="text-nowrap text-end">339.0<b class="d-inline-block" style="font-size:80%;width:2em;">kcal</b></td>
                <th class=""></th>
                <td class="text-nowrap text-end"></td>
            </tr>
            <tr>
                <th class="">炭水化物</th>
                <td class="text-nowrap text-end">79.2<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">たんぱく質</th>
                <td class="text-nowrap text-end">5.4<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            <tr>
                <th class="">脂質</th>
                <td class="text-nowrap text-end">0.9<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">食物繊維</th>
                <td class="text-nowrap text-end">3.3<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            </tbody>
            </table>
        </div>

        <h6 class="mb-0">サラダ</h6>
        <div class="scrs-sheet-normal ps-4 pe-2 py-2 d-flex justify-content-center mb-2">
            <table class="col-12 col-sm-11 col-md-10 col-lg-8 col-xl-6">
            <tbody>
            <tr>
                <th class="">エネルギー</th>
                <td class="text-nowrap text-end">5.9<b class="d-inline-block" style="font-size:80%;width:2em;">kcal</b></td>
                <th class=""></th>
                <td class="text-nowrap text-end"></td>
            </tr>
            <tr>
                <th class="">炭水化物</th>
                <td class="text-nowrap text-end">5.9<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">たんぱく質</th>
                <td class="text-nowrap text-end">2.6<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            <tr>
                <th class="">脂質</th>
                <td class="text-nowrap text-end">1.0<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">食物繊維</th>
                <td class="text-nowrap text-end">2.4<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            </tbody>
            </table>
        </div>

        <h6 class="mb-0">糠付け</h6>
        <div class="scrs-sheet-normal ps-4 pe-2 py-2 d-flex justify-content-center mb-2">
            <table class="col-12 col-sm-11 col-md-10 col-lg-8 col-xl-6">
            <tbody>
            <tr>
                <th class="">エネルギー</th>
                <td class="text-nowrap text-end">3.2<b class="d-inline-block" style="font-size:80%;width:2em;">kcal</b></td>
                <th class=""></th>
                <td class="text-nowrap text-end"></td>
            </tr>
            <tr>
                <th class="">炭水化物</th>
                <td class="text-nowrap text-end">3.2<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">たんぱく質</th>
                <td class="text-nowrap text-end">0.4<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            <tr>
                <th class="">脂質</th>
                <td class="text-nowrap text-end">0.1<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">食物繊維</th>
                <td class="text-nowrap text-end">2.0<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-transparent p-1">
        <h6 class="mb-0">total</h6>
        <div class="scrs-sheet-normal ps-4 pe-2 py-2 d-flex justify-content-center mb-2">
            <table class="col-12 col-sm-11 col-md-10 col-lg-8 col-xl-6">
            <tbody>
            <tr>
                <th class="">エネルギー</th>
                <td class="text-nowrap text-end">932.2<b class="d-inline-block" style="font-size:80%;width:2em;">kcal</b></td>
                <th class=""></th>
                <td class="text-nowrap text-end"></td>
            </tr>
            <tr>
                <th class="">炭水化物</th>
                <td class="text-nowrap text-end">126.2<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">たんぱく質</th>
                <td class="text-nowrap text-end">46.4<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            <tr>
                <th class="">脂質</th>
                <td class="text-nowrap text-end">32.6<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">食物繊維</th>
                <td class="text-nowrap text-end">13.3<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            </tbody>
            </table>
        </div>

    </div>
</div>

<br>

{{-- お弁当（試合） --}}
<div class="card">
    <div class="card-header p-1">
        <h5 class="mb-0">お弁当（試合）</h5>
    </div>
    <div class="card-body p-1">
        <h6 class="mb-0">鶏モモ甘酢煮</h6>
        <div class="scrs-sheet-normal ps-4 pe-2 py-2 d-flex justify-content-center mb-2">
            <table class="col-12 col-sm-11 col-md-10 col-lg-8 col-xl-6">
            <tbody>
            <tr>
                <th class="">エネルギー</th>
                <td class="text-nowrap text-end">377.0<b class="d-inline-block" style="font-size:80%;width:2em;">kcal</b></td>
                <th class=""></th>
                <td class="text-nowrap text-end"></td>
            </tr>
            <tr>
                <th class="">炭水化物</th>
                <td class="text-nowrap text-end">13.4<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">たんぱく質</th>
                <td class="text-nowrap text-end">26.0<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            <tr>
                <th class="">脂質</th>
                <td class="text-nowrap text-end">21.3<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">食物繊維</th>
                <td class="text-nowrap text-end">0.0<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            </tbody>
            </table>
        </div>

        <h6 class="mb-0">肉じゃが</h6>
        <div class="scrs-sheet-normal ps-4 pe-2 py-2 d-flex justify-content-center mb-2">
            <table class="col-12 col-sm-11 col-md-10 col-lg-8 col-xl-6">
            <tbody>
            <tr>
                <th class="">エネルギー</th>
                <td class="text-nowrap text-end">58.5<b class="d-inline-block" style="font-size:80%;width:2em;">kcal</b></td>
                <th class=""></th>
                <td class="text-nowrap text-end"></td>
            </tr>
            <tr>
                <th class="">炭水化物</th>
                <td class="text-nowrap text-end">5.2<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">たんぱく質</th>
                <td class="text-nowrap text-end">2.0<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            <tr>
                <th class="">脂質</th>
                <td class="text-nowrap text-end">3.6<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">食物繊維</th>
                <td class="text-nowrap text-end">1.9<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            </tbody>
            </table>
        </div>

        <h6 class="mb-0">春雨サラダ</h6>
        <div class="scrs-sheet-normal ps-4 pe-2 py-2 d-flex justify-content-center mb-2">
            <table class="col-12 col-sm-11 col-md-10 col-lg-8 col-xl-6">
            <tbody>
            <tr>
                <th class="">エネルギー</th>
                <td class="text-nowrap text-end">69.8<b class="d-inline-block" style="font-size:80%;width:2em;">kcal</b></td>
                <th class=""></th>
                <td class="text-nowrap text-end"></td>
            </tr>
            <tr>
                <th class="">炭水化物</th>
                <td class="text-nowrap text-end">7.7<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">たんぱく質</th>
                <td class="text-nowrap text-end">2.8<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            <tr>
                <th class="">脂質</th>
                <td class="text-nowrap text-end">3.6<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">食物繊維</th>
                <td class="text-nowrap text-end">0.6<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            </tbody>
            </table>
        </div>

        <h6 class="mb-0">ぬたみそ</h6>
        <div class="scrs-sheet-normal ps-4 pe-2 py-2 d-flex justify-content-center mb-2">
            <table class="col-12 col-sm-11 col-md-10 col-lg-8 col-xl-6">
            <tbody>
            <tr>
                <th class="">エネルギー</th>
                <td class="text-nowrap text-end">34.4<b class="d-inline-block" style="font-size:80%;width:2em;">kcal</b></td>
                <th class=""></th>
                <td class="text-nowrap text-end"></td>
            </tr>
            <tr>
                <th class="">炭水化物</th>
                <td class="text-nowrap text-end">7.7<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">たんぱく質</th>
                <td class="text-nowrap text-end">0.8<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            <tr>
                <th class="">脂質</th>
                <td class="text-nowrap text-end">0.2<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">食物繊維</th>
                <td class="text-nowrap text-end">0.8<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            </tbody>
            </table>
        </div>

        <h6 class="mb-0">味噌汁</h6>
        <div class="scrs-sheet-normal ps-4 pe-2 py-2 d-flex justify-content-center mb-2">
            <table class="col-12 col-sm-11 col-md-10 col-lg-8 col-xl-6">
            <tbody>
            <tr>
                <th class="">エネルギー</th>
                <td class="text-nowrap text-end">44.5<b class="d-inline-block" style="font-size:80%;width:2em;">kcal</b></td>
                <th class=""></th>
                <td class="text-nowrap text-end"></td>
            </tr>
            <tr>
                <th class="">炭水化物</th>
                <td class="text-nowrap text-end">4.0<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">たんぱく質</th>
                <td class="text-nowrap text-end">6.3<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            <tr>
                <th class="">脂質</th>
                <td class="text-nowrap text-end">3.8<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">食物繊維</th>
                <td class="text-nowrap text-end">2.2<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            </tbody>
            </table>
        </div>

        <h6 class="mb-0">白米</h6>
        <div class="scrs-sheet-normal ps-4 pe-2 py-2 d-flex justify-content-center mb-2">
            <table class="col-12 col-sm-11 col-md-10 col-lg-8 col-xl-6">
            <tbody>
            <tr>
                <th class="">エネルギー</th>
                <td class="text-nowrap text-end">339.0<b class="d-inline-block" style="font-size:80%;width:2em;">kcal</b></td>
                <th class=""></th>
                <td class="text-nowrap text-end"></td>
            </tr>
            <tr>
                <th class="">炭水化物</th>
                <td class="text-nowrap text-end">79.2<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">たんぱく質</th>
                <td class="text-nowrap text-end">5.4<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            <tr>
                <th class="">脂質</th>
                <td class="text-nowrap text-end">0.9<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">食物繊維</th>
                <td class="text-nowrap text-end">3.3<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            </tbody>
            </table>
        </div>

        <h6 class="mb-0">サラダ</h6>
        <div class="scrs-sheet-normal ps-4 pe-2 py-2 d-flex justify-content-center mb-2">
            <table class="col-12 col-sm-11 col-md-10 col-lg-8 col-xl-6">
            <tbody>
            <tr>
                <th class="">エネルギー</th>
                <td class="text-nowrap text-end">5.9<b class="d-inline-block" style="font-size:80%;width:2em;">kcal</b></td>
                <th class=""></th>
                <td class="text-nowrap text-end"></td>
            </tr>
            <tr>
                <th class="">炭水化物</th>
                <td class="text-nowrap text-end">5.9<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">たんぱく質</th>
                <td class="text-nowrap text-end">2.6<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            <tr>
                <th class="">脂質</th>
                <td class="text-nowrap text-end">1.0<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">食物繊維</th>
                <td class="text-nowrap text-end">2.4<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            </tbody>
            </table>
        </div>

        <h6 class="mb-0">糠付け</h6>
        <div class="scrs-sheet-normal ps-4 pe-2 py-2 d-flex justify-content-center mb-2">
            <table class="col-12 col-sm-11 col-md-10 col-lg-8 col-xl-6">
            <tbody>
            <tr>
                <th class="">エネルギー</th>
                <td class="text-nowrap text-end">3.2<b class="d-inline-block" style="font-size:80%;width:2em;">kcal</b></td>
                <th class=""></th>
                <td class="text-nowrap text-end"></td>
            </tr>
            <tr>
                <th class="">炭水化物</th>
                <td class="text-nowrap text-end">3.2<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">たんぱく質</th>
                <td class="text-nowrap text-end">0.4<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            <tr>
                <th class="">脂質</th>
                <td class="text-nowrap text-end">0.1<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">食物繊維</th>
                <td class="text-nowrap text-end">2.0<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-transparent p-1">
        <h6 class="mb-0">total</h6>
        <div class="scrs-sheet-normal ps-4 pe-2 py-2 d-flex justify-content-center mb-2">
            <table class="col-12 col-sm-11 col-md-10 col-lg-8 col-xl-6">
            <tbody>
            <tr>
                <th class="">エネルギー</th>
                <td class="text-nowrap text-end">932.2<b class="d-inline-block" style="font-size:80%;width:2em;">kcal</b></td>
                <th class=""></th>
                <td class="text-nowrap text-end"></td>
            </tr>
            <tr>
                <th class="">炭水化物</th>
                <td class="text-nowrap text-end">126.2<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">たんぱく質</th>
                <td class="text-nowrap text-end">46.4<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            <tr>
                <th class="">脂質</th>
                <td class="text-nowrap text-end">32.6<b class="d-inline-block text-start" style="font-size:80%;width:2em;">g</b></td>
                <th class="">食物繊維</th>
                <td class="text-nowrap text-end">13.3<b class="d-inline-block text-start" style="font-size:80%;">g</b></td>
            </tr>
            </tbody>
            </table>
        </div>

    </div>
</div>

@endsection
