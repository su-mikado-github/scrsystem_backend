@extends('layouts.admin')

@use(Carbon\Carbon)
@use(App\AffiliationDetailTypes)
@use(App\Flags)
@use(App\Weekdays)
@use(App\SortTypes)

<x-script>
import { SCRSPage } from "/scrs-pages.js";
import { SCRSConfirmDialog } from "/dialogs/confirm-dialog.js";

class AdminDishMenuPage extends SCRSPage {

    #commit = null;

    #energy = null;
    #carbohydrates = null;
    #protein = null;
    #lipid = null;
    #dietaryFiber = null;

    #totalEnergy = null;
    #totalCarbohydrates = null;
    #totalProtein = null;
    #totalLipid = null;
    #totalDietaryFiber = null;

    #compute(fields, totalField) {
        totalField.value = (fields.map((f)=>Math.floor(Number(f.value)*10)).reduce((t,v)=>(t+v), 0) / 10);
    }

    constructor() {
        super();
        //
        this.#commit = this.action("commit", [ "click" ]);

        this.#energy = this.fields("energy").map((f)=>f.handle("change"));
        this.#carbohydrates = this.fields("carbohydrates").map((f)=>f.handle("change"));
        this.#protein = this.fields("protein").map((f)=>f.handle("change"));
        this.#lipid = this.fields("lipid").map((f)=>f.handle("change"));
        this.#dietaryFiber = this.fields("dietaryFiber").map((f)=>f.handle("change"));

        this.#totalEnergy = this.field("totalEnergy");
        this.#totalCarbohydrates = this.field("totalCarbohydrates");
        this.#totalProtein = this.field("totalProtein");
        this.#totalLipid = this.field("totalLipid");
        this.#totalDietaryFiber = this.field("totalDietaryFiber");

        this.#compute(this.#energy, this.#totalEnergy);
        this.#compute(this.#carbohydrates, this.#totalCarbohydrates);
        this.#compute(this.#protein, this.#totalProtein);
        this.#compute(this.#lipid, this.#totalLipid);
        this.#compute(this.#dietaryFiber, this.#totalDietaryFiber);
    }

    commit_click(e) {
        this.put([ "/admin/dish_menus", @json($dish_type->key), @json($date->format('Y-m-d')) ]);
    }

    energy_change(e) {
        this.#compute(this.#energy, this.#totalEnergy);
    }

    carbohydrates_change(e) {
        this.#compute(this.#carbohydrates, this.#totalCarbohydrates);
    }

    protein_change(e) {
        this.#compute(this.#protein, this.#totalProtein);
    }

    lipid_change(e) {
        this.#compute(this.#lipid, this.#totalLipid);
    }

    dietaryFiber_change(e) {
        this.#compute(this.#dietaryFiber, this.#totalDietaryFiber);
    }
}

SCRSPage.startup(()=>new AdminDishMenuPage());
</x-script>

@section('page.title')
<span>管理画面<span><x-icon name="fa-solid fa-angles-right" /><span>メニュー編集<span><x-icon name="fa-solid fa-angles-right" /><span>詳細<span>
@endsection

@section('side-menu')
<x-virtical-menu id="idLeftSideMenu" category="left.side.menu" item_key="dish_menus" />
@endsection

@section('main')
<div class="row">
    <div class="col-8">
        <div class="row">
            <div class="col-4">
                <a class="btn btn-link text-body" href="{!! url('/admin/dish_menus', [ $dish_type->key ]) !!}?year_month={!! $date->format('Y-m') !!}">≪メニュー編集に戻る</a>
            </div>
            <div class="col-2 text-end">
                <a class="btn btn-link text-body" href="{!! url('/admin/dish_menus', [ $dish_type->key, $date->copy()->subDays()->format('Y-m-d') ]) !!}">≪前日</a>
            </div>
            <div class="col-2 text-center py-2">
                <span class="text-nowrap3">{!! $date->format('Y年m月d日') !!}</span>
            </div>
            <div class="col-2 text-center">
                <a class="btn btn-link text-body" href="{!! url('/admin/dish_menus', [ $dish_type->key, today()->format('Y-m-d') ]) !!}">当日</a>
            </div>
            <div class="col-2 text-start">
                <a class="btn btn-link text-body" href="{!! url('/admin/dish_menus', [ $dish_type->key, $date->copy()->addDays()->format('Y-m-d') ]) !!}">次日≫</a>
            </div>
        </div>
    </div>
    <div class="col-4 text-end">
        <button data-action="commit" type="button" class="btn scrs-main-button" style="width:8em;">更新</button>
    </div>
</div>

<br>
<table class="table table-bordered" style="width:90%;">
<colgroup>
    <col style="">
    <col style="width:11em;">
    <col style="width:9em;">
    <col style="width:9em;">
    <col style="width:9em;">
    <col style="width:9em;">
</colgroup>
<thead class="scrs-bg-main">
<tr>
    <th>料理メニュー</th>
    <th>エネルギー</th>
    <th>炭水化物</th>
    <th>タンパク質</th>
    <th>脂質</th>
    <th>食物繊維</th>
</tr>
</thead>
<tbody>
@forelse($dish_menus as $dish_menu)
<tr>
    <input type="hidden" name="dish_menu_id[]" value="{{ $dish_menu->id }}">
    <td>
        <input type="text" class="form-control" name="name[]" value="{{ $dish_menu->name }}">
    </td>
    <td>
        <div class="input-group">
            <input type="number" class="form-control text-end" name="energy[]" data-field="energy" value="{{ $dish_menu->energy }}">
            <span class="input-group-text border-top-0 border-bottom-0 border-end-0 bg-transparent px-1">kcal</span>
        </div>
    </td>
    <td>
        <div class="input-group">
            <input type="number" class="form-control text-end" name="carbohydrates[]" data-field="carbohydrates" value="{{ $dish_menu->carbohydrates }}">
            <span class="input-group-text border-top-0 border-bottom-0 border-end-0 bg-transparent px-1">g</span>
        </div>
    </td>
    <td>
        <div class="input-group">
            <input type="number" class="form-control text-end" name="protein[]" data-field="protein" value="{{ $dish_menu->protein }}">
            <span class="input-group-text border-top-0 border-bottom-0 border-end-0 bg-transparent px-1">g</span>
        </div>
    </td>
    <td>
        <div class="input-group">
            <input type="number" class="form-control text-end" name="lipid[]" data-field="lipid" value="{{ $dish_menu->lipid }}">
            <span class="input-group-text border-top-0 border-bottom-0 border-end-0 bg-transparent px-1">g</span>
        </div>
    </td>
    <td>
        <div class="input-group">
            <input type="number" class="form-control text-end" name="dietary_fiber[]" data-field="dietaryFiber" value="{{ $dish_menu->dietary_fiber }}">
            <span class="input-group-text border-top-0 border-bottom-0 border-end-0 bg-transparent px-1">g</span>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="6">※メニューは登録されていません。</td>
</tr>
@endforelse
</tbody>
</table>

<br>
<table class="table table-bordered" style="width:90%;">
<colgroup>
    <col style="">
    <col style="width:11em;">
    <col style="width:9em;">
    <col style="width:9em;">
    <col style="width:9em;">
    <col style="width:9em;">
</colgroup>
<tbody>
    <tr class="scrs-bg-main">
        <th rowspan="2" class="align-bottom">total ※自動計算</th>
        <th>エネルギー</th>
        <th>炭水化物</th>
        <th>タンパク質</th>
        <th>脂質</th>
        <th>食物繊維</th>
    </tr>
    <tr>
        <td>
            <div class="input-group">
                <input type="number" class="form-control text-end" data-field="totalEnergy" readonly>
                <span class="input-group-text border-top-0 border-bottom-0 border-end-0 bg-transparent px-1">kcal</span>
            </div>
        </td>
        <td>
            <div class="input-group">
                <input type="number" class="form-control text-end" data-field="totalCarbohydrates" readonly>
                <span class="input-group-text border-top-0 border-bottom-0 border-end-0 bg-transparent px-1">g</span>
            </div>
        </td>
        <td>
            <div class="input-group">
                <input type="number" class="form-control text-end" data-field="totalProtein" readonly>
                <span class="input-group-text border-top-0 border-bottom-0 border-end-0 bg-transparent px-1">g</span>
            </div>
        </td>
        <td>
            <div class="input-group">
                <input type="number" class="form-control text-end" data-field="totalLipid" readonly>
                <span class="input-group-text border-top-0 border-bottom-0 border-end-0 bg-transparent px-1">g</span>
            </div>
        </td>
        <td>
            <div class="input-group">
                <input type="number" class="form-control text-end" data-field="totalDietaryFiber" readonly>
                <span class="input-group-text border-top-0 border-bottom-0 border-end-0 bg-transparent px-1">g</span>
            </div>
        </td>
    </tr>
</tbody>
</table>
@endsection
