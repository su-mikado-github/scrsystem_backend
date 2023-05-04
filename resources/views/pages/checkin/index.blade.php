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
        this.#checkin = this.action("checkin")?.handle("click");

        this.#qrCodeReaderDialog = new SCRSQrCodeReaderDialog(this, "qrCodeReader", null, [ "read" ]);
    }

    checkin_click(e) {
        //
        this.#qrCodeReaderDialog.open();
    }

    qrCodeReader_read(e) {
        console.log(e.detail);
        alert(e.detail.code);
        this.#qrCodeReaderDialog.close();
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
    <h3 class="text-center mb-4">ご予約内容</h3>
    <dl class="scrs-item-group mb-0">
        <dt class="label">日時</dt>
        <dd class="item"><span>00月00日</span><span class="px-2"></span><span>00:00～</span></dd>
        <dt class="label">人数</dt>
        <dd class="item"><span>00</span>人</dd>
    </dl>
</div>

<br>

<div class="d-flex justify-content-center">
    <button type="button" data-action="checkin" class="btn btn-lg scrs-main-button col-10 py-2">チェックインする</button>
</div>

<br>

<div class="px-5 py-4 scrs-sheet-ticket">
    <h3 class="text-center mb-2">回数券残数</h3>
    <div class="h2 text-center">
        <span class="text-nowrap">
            <span class="font-weight-normal">残り</span>
            <span class="px-2" style="font-size:200%;">00</span>
            <span class="font-weight-normal">回</span>
        </span>
    </div>
</div>
@endsection

<x-qr-code-reader-dialog id="qrCodeReader">
    <x-slot name="title">&nbsp;</x-slot>
</x-qr-code-reader-dialog>
