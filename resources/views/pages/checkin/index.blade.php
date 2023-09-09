@use(App\ReserveTypes)
@extends('layouts.default')

<x-script>
import { SCRSPage } from "/scrs-pages.js";
import { SCRSQrCodeReaderDialog } from "/dialogs/qr-code-reader-dialog.js";

class CheckinPage extends SCRSPage {
    @if(isset($reserve) && !isset($reserve->checkin_dt))
    #checkin = null;

    #qrCodeReaderDialog = null;
    @endif

    constructor() {
        super();
        //
        @if(isset($reserve) && !isset($reserve->checkin_dt))
        this.#checkin = this.action("checkin", [ "click" ]);

        this.#qrCodeReaderDialog = new SCRSQrCodeReaderDialog(this, "qrCodeReader", null, [ "read" ]);
        @endif
    }

    @if(isset($reserve) && !isset($reserve->checkin_dt))
    checkin_click(e) {
        //
        this.#qrCodeReaderDialog.open();
    }

    qrCodeReader_read(e) {
        console.log(e.detail);
        this.#qrCodeReaderDialog.close();
        if (e.detail.code) {
            this.post([ e.detail.code, @json($reserve->id) ]);
        }
    }
    @endif
}

SCRSPage.startup(()=>new CheckinPage());
</x-script>

@section('page.title')
@if(op($reserve)->type == ReserveTypes::LUNCHBOX)お弁当 @else チェックイン @endif
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

@if(isset($reserve))
@eval(list($label, $unit, $action) = ($reserve->type == ReserveTypes::LUNCHBOX ? [ '個数', '個', '受け取る' ] : [ '人数', '人', 'チェックインする' ]))
<br>
@isset($other_reserve)
@eval($other_label = ($other_reserve->type == ReserveTypes::LUNCHBOX ? 'お弁当のご予約' : '食堂のご予約'))
<div class="text-end">
    <a class="btn btn-link scrs-text-main" href="{!! route('checkin.reserve', [ 'reserve_id'=>$other_reserve->id ]) !!}">{{ $other_label }}&nbsp;≫</a>
</div>
@endisset
<div class="px-5 py-4 scrs-sheet-normal">
    <h3 class="text-center mb-4">ご予約内容</h3>
    <dl class="scrs-item-group mb-0">
        <dt class="label">日時</dt>
        <dd class="item"><span>{{ $reserve->date->format('m月d日') }}</span>@isset($reserve->time)<span class="px-2"></span><span>{{ $reserve->time }}～</span>@endisset</dd>
        <dt class="label">{{ $label }}</dt>
        <dd class="item"><span>{{ $reserve->reserve_count }}</span>{{ $unit }}</dd>
    </dl>

    <br>
    <div class="d-flex justify-content-center">
        @isset($reserve->checkin_dt)
        <span class="btn btn-lg btn-secondary col-10 py-2">完了</span>
        @else
        <button type="button" data-action="checkin" class="btn btn-lg scrs-main-button col-10 py-2">{{ $action }}</button>
        @endisset
    </div>
</div>

@else
<div class="px-5 py-4 scrs-sheet-normal">
    <h3 class="text-center mb-4">ご予約内容</h3>
    <p>※本日のご予約はありません。</p>
</div>
@endif

<br>
<div class="px-5 py-4 scrs-sheet-ticket">
    <h3 class="text-center mb-2">回数券残数</h3>
    <div class="h2 text-center">
        <span class="text-nowrap">
            <span class="font-weight-normal">残り</span>
            <span class="px-2" style="font-size:200%;">{!! $user->last_ticket_count ?? 0 !!}</span>
            <span class="font-weight-normal">回</span>
        </span>
        @if(($user->unpaid_ticket_count ?? 0) > 0)
        <span class="text-nowrap">
            <span class="font-weight-normal" style="font-size:80%;">（内未清算</span>
            <span class="px-1" style="font-size:80%;">{{ $user->unpaid_ticket_count ?? 0 }}</span>
            <span class="font-weight-normal" style="font-size:80%;">回）</span>
        </span>
        @endif
    </div>
</div>
@endsection

@if(isset($reserve) && !isset($reserve->checkin_dt))
<x-qr-code-reader-dialog id="qrCodeReader">
    <x-slot name="title">&nbsp;</x-slot>
</x-qr-code-reader-dialog>
@endif
