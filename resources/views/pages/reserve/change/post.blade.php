@extends('layouts.default')

<x-script>
import { SCRSPage } from "/scrs-pages.js";
{{-- import { SCRSQrCodeReaderDialog } from "/dialogs/qr-code-reader-dialog.js";

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
        this.#qrCodeReaderDialog.close();
    }
}

SCRSPage.startup(()=>new CheckinPage()); --}}
</x-script>

@section('page.title')
予約時間の変更完了
@endsection

@section('main')
<p>予約時間を変更しました。<br>
右上の×ボタンを押下して画面を閉じてください。</p>
@endsection
