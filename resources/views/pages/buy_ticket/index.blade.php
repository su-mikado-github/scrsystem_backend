@extends('layouts.default')

<x-script>
import { SCRSPage } from "/scrs-pages.js";
import { SCRSConfirmDialog } from "/dialogs/confirm-dialog.js";

class BuyTicketPage extends SCRSPage {
    @foreach($tickets as $ticket)
    #ticket{!! $ticket->id !!} = null;
    @endforeach

    #buyConfirmDialog = null;
    #buyConfirmDialogTicketCount = null;

    #ticketName = null;
    #ticketId = null;

    constructor() {
        super();
        //
        @foreach($tickets as $ticket)
        this.#ticket{!! $ticket->id !!} = this.action("ticket{!! $ticket->id !!}", [ "click" ]);
        @endforeach

        this.#buyConfirmDialog = new SCRSConfirmDialog(this, "buyConfirm", null, [ "show", "hide", "ok" ]);
        this.#buyConfirmDialogTicketCount = this.#buyConfirmDialog.field("ticket_count", "name");
    }

    buyConfirm_show(e) {
//        e.preventDefault();
    }

    buyConfirm_hide(e) {

    }

    buyConfirm_ok(e) {
        this.post([ "/buy_ticket", this.#ticketId ]);
    }

    @foreach($tickets as $ticket)
    ticket{!! $ticket->id !!}_click(e) {
        e.preventDefault();
        e.stopPropagation();

        this.#buyConfirmDialogTicketCount.innerText = @json($ticket->name);
        this.#ticketId = e.target.dataset['id'];
    }
    @endforeach
}

SCRSPage.startup(()=>new BuyTicketPage());
</x-script>

@section('page.title')
回数券の購入
@endsection

@section('main')
@if(session()->has('backward'))<input type="hidden" name="backward" value="{!! session('backward') !!}">@endif
@if(session()->has('lunchbox_count'))<input type="hidden" name="lunchbox_count" value="{!! session('lunchbox_count') !!}">@endif
@if(session()->has('person_count'))<input type="hidden" name="person_count" value="{!! session('person_count') !!}">@endif
@if(session()->has('is_table_share'))<input type="hidden" name="is_table_share" value="{!! session('is_table_share') !!}">@endif

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

<div class="px-5 py-4 scrs-sheet-ticket">
    <h3 class="text-center mb-2">回数券残数</h3>
    <div class="h2 text-center">
        <span class="text-nowrap">
            <span class="font-weight-normal">残り</span>
            <span class="px-2" style="font-size:200%;">{{ $last_ticket_count ?? 0 }}</span>
            <span class="font-weight-normal">回</span>
        </span>
        @if($unpaid_ticket_count > 0)
        <span class="text-nowrap">
            <span class="font-weight-normal" style="font-size:80%;">（内未清算</span>
            <span class="px-1" style="font-size:80%;">{{ $unpaid_ticket_count ?? 0 }}</span>
            <span class="font-weight-normal" style="font-size:80%;">回）</span>
        </span>
        @endif
    </div>
</div>

<br>

@if(($last_ticket_count ?? 0) == 0)
<h5 class="text-center">回数券の残数が無くなりました。</h5>
<h5 class="text-center">ご購入をお願いします。</h5>
@endif

<br>

@foreach($tickets as $ticket)
<div class="d-flex justify-content-center py-2">
    <button type="button" data-action="ticket{!! $ticket->id !!}" data-id="{!! $ticket->id !!}" class="btn border border-1 scrs-bg-sub-button scrs-border-main col-8" data-bs-toggle="modal" data-bs-target="#buyConfirm">{{ $ticket->name }}</button>
</div>
@endforeach

@error('buy_ticket_count')<p class="text-danger">{{ $message }}</p>@enderror
@endsection

<x-confirm-dialog id="buyConfirm" type="confirm">
    <x-slot name="title">確認</x-slot>
    <h3 data-name="message" class="text-center mb-3"><span data-name="ticket_count"></span>の回数券を購入します</h3>
    <p data-name="description" class="text-center">※回数券が不足している場合はご予約は出来ませんので、ご注意ください。</p>
    <x-slot name="ok_button">回数券を購入する</x-slot>
    <x-slot name="cancel_button">戻る</x-slot>
</x-confirm-dialog>
