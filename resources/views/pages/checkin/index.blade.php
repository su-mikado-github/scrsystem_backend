@use(App\ReserveTypes)
@extends('layouts.default')

<x-script>
import { SCRSPage } from "/scrs-pages.js";
import { SCRSQrCodeReaderDialog } from "/dialogs/qr-code-reader-dialog.js";

class CheckinPage extends SCRSPage {
    #checkin = null;

    #qrCodeReaderDialog = null;

    constructor() {
        super();
        //
        this.#checkin = this.action("checkin", [ "click" ]);

        this.#qrCodeReaderDialog = new SCRSQrCodeReaderDialog(this, "qrCodeReader", null, [ "read" ]);
    }

    checkin_click(e) {
        //
        this.#qrCodeReaderDialog.open();
    }

    qrCodeReader_read(e) {
        console.log(e.detail);
        this.#qrCodeReaderDialog.close();
        this.forward("/checkin/complete");
    }
}

SCRSPage.startup(()=>new CheckinPage());
</x-script>

@section('page.title')
チェックイン
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

@isset($reserve)
@eval(list($label, $unit, $action) = ($reserve->type == ReserveTypes::LUNCHBOX ? [ '個数', '個', 'お弁当を受け取る' ] : [ '人数', '人', 'チェックインする' ]))
<br>
<div class="px-5 py-4 scrs-sheet-normal">
    <h3 class="text-center mb-4">ご予約内容</h3>
    <dl class="scrs-item-group mb-0">
        <dt class="label">日時</dt>
        <dd class="item"><span>{{ $reserve->date->format('m月d日') }}</span>@isset($reserve->time)<span class="px-2"></span><span>{{ $reserve->time }}～</span>@endisset</dd>
        <dt class="label">{{ $label }}</dt>
        <dd class="item"><span>{{ $reserve->reserve_count }}</span>{{ $unit }}</dd>
    </dl>
</div>

<br>
<div class="d-flex justify-content-center">
    <button type="button" data-action="checkin" class="btn btn-lg scrs-main-button col-10 py-2">{{ $action }}</button>
</div>
@else
<div class="px-5 py-4 scrs-sheet-normal">
    <h3 class="text-center mb-4">ご予約内容</h3>
    <p>※本日のご予約はありません。</p>
</div>
@endisset

<br>
<div class="px-5 py-4 scrs-sheet-ticket">
    <h3 class="text-center mb-2">回数券残数</h3>
    <div class="h2 text-center">
        <span class="text-nowrap">
            <span class="font-weight-normal">残り</span>
            <span class="px-2" style="font-size:200%;">{!! $user->last_ticket_count ?? 0 !!}</span>
            <span class="font-weight-normal">回</span>
        </span>
    </div>
</div>
@endsection

<x-qr-code-reader-dialog id="qrCodeReader">
    <x-slot name="title">&nbsp;</x-slot>
</x-qr-code-reader-dialog>
