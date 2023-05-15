@extends('layouts.default')

<x-script>
import { SCRSPage } from "/scrs-pages.js";
import { SCRSConfirmDialog } from "/dialogs/confirm-dialog.js";

class ChangePage extends SCRSPage {
    #changeConfirmDialog = null;
    #cancelConfirmDialog = null;

    constructor() {
        super();
        //
        this.#changeConfirmDialog = new SCRSConfirmDialog(this, "changeConfirm", null, [ "show", "hide", "ok" ]);
        this.#cancelConfirmDialog = new SCRSConfirmDialog(this, "cancelConfirm", null, [ "show", "hide", "ok" ]);
    }

    changeConfirm_show(e) {
//        e.preventDefault();
    }

    changeConfirm_hide(e) {

    }

    changeConfirm_ok(e) {
        this.post([ "/reserve/change", 1 ]);
        {{-- this.#changeConfirmDialog.close(); --}}
    }

    cancelConfirm_show(e) {
//        e.preventDefault();
    }

    cancelConfirm_hide(e) {

    }

    cancelConfirm_ok(e) {
        this.delete([ "/reserve/change", 1 ]);
        {{-- this.#cancelConfirmDialog.close(); --}}
    }
}

SCRSPage.startup(()=>new ChangePage());
</x-script>

@section('page.title')
ご予約内容の変更
@endsection

@section('main')
<h2>
    <ruby>
        <rb>{{ $user->last_name }}</rb>
        <rt>{{ $user->last_name_kana }}</rt>
    </ruby>
    <ruby>
      <rb>{{ $user->first_name }}</rb>
      <rt>{{ $user->first_name_kana }}</rt>
    </ruby>
    様
</h2>

<br>

<div class="px-5 py-4 scrs-sheet-normal">
    <h3 class="text-center mb-4">ご予約内容</h3>
    <dl class="scrs-item-group mb-0">
        <dt class="label">日時</dt>
        <dd class="item"><span>00月00日</span><span class="px-2"></span><span>00:00～</span></dd>
        <dt class="label">人数</dt>
        <dd class="item"><span>00</span>人</dd>
    </dl>
</div>

<br>

<p>お時間の変更の際は、下記の<span class="mdi mdi-circle-outline scrs-text-available fs-4"></span>または<span class="mdi mdi-triangle-outline scrs-text-few-left fs-4"></span>の時間を選択してください。</p>

<table class="w-100" style="border-collapse:separate;border-spacing:2px;">
<colgroup>
    <col style="width:25%;">
    <col style="width:75%;">
</colgroup>
<thead>
@foreach($time_schedules as $time_schedule)
@eval(list($hour, $minute, $second) = explode(':', $time_schedule->time))
<tr class="scrs-bg">
    <td class="bg-white text-center align-middle py-1">{{ sprintf('%02d:%02d', $hour, $minute) }}</td>
    <td class="bg-white text-center align-middle py-1"><span class="mdi mdi-circle-outline scrs-text-available fs-4"></span></td>
</tr>
@endforeach
</thead>
</table>

<br>

<div class="d-flex justify-content-center py-2">
    <button data-action="change" type="button" class="btn scrs-bg-main-button col-8" data-bs-toggle="modal" data-bs-target="#changeConfirm">予約変更</button>
</div>

<div class="d-flex justify-content-center py-2">
    <button data-action="cancel" type="button" class="btn border border-1 scrs-bg-sub-button scrs-border-main col-8" data-bs-toggle="modal" data-bs-target="#cancelConfirm">予約取消</button>
</div>

<div class="d-flex justify-content-center py-2">
    <div class="col-8">
        <small class="scrs-text-main">※予約取消では、完全に予約が取り消されますので、ご注意ください。</small>
    </div>
</div>

@endsection

<x-confirm-dialog id="changeConfirm" type="change">
    <x-slot name="title">確認</x-slot>
    <h3 data-name="message" class="text-center mb-3">1月8日(土)　09:50～</h3>
    <p data-name="description" class="text-center">※混雑時はお待ちいただく場合がございます</p>
    <x-slot name="ok_button">予約を変更する</x-slot>
    <x-slot name="cancel_button">戻る</x-slot>
</x-confirm-dialog>

<x-confirm-dialog id="cancelConfirm" type="cancel">
    <x-slot name="title">確認</x-slot>
    <h3 data-name="message" class="text-center mb-3">1月8日(土)　09:50～</h3>
    <p data-name="description" class="text-center">※完全に予約が取り消されますのでご注意ください。</p>
    <x-slot name="ok_button">予約を取り消す</x-slot>
    <x-slot name="cancel_button">戻る</x-slot>
</x-confirm-dialog>
