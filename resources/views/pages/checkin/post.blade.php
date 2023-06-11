@use(App\ReserveTypes)
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
@if($reserve->type == ReserveTypes::LUNCHBOX)受け取り完了 @else チェックイン完了 @endif
@endsection

@section('main')
<p>{!! ($reserve->type == ReserveTypes::LUNCHBOX ? 'お弁当の受け取り' : 'チェックイン') !!}が完了しました。<br>
右上の×ボタンを押下して画面を閉じてください。</p>
@endsection
