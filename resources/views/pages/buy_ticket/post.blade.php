@extends('layouts.default')

<x-script>
import { SCRSPage } from "/scrs-pages.js";

class BuyTicketPage extends SCRSPage {
    #pageClose = null;

    constructor() {
        super();
        //
        this.#pageClose = this.action("pageClose", [ "click" ]);
    }

    pageClose_click(e) {
        window.close();
    }
}

SCRSPage.startup(()=>new BuyTicketPage());
</x-script>

@section('page.title')
回数券の購入完了
@endsection

@section('main')
<p>{{ $ticket->name }}回数券を購入しました。<br>
食堂にて回数券の支払をお願いします。</p>
<p class="text-danger">支払確認後に回数券が承諾されます。</p>
<br>
<p>※当画面は、右上の×ボタンを押下して画面を閉じてください。</p>

<div class="px-5 py-4 scrs-sheet-ticket">
    <h3 class="text-center mb-2">回数券残数</h3>
    <div class="h2 text-center">
        <span class="text-nowrap">
            <span class="font-weight-normal">残り</span>
            <span class="px-2" style="font-size:200%;">{{ ($user->last_ticket_count ?? 0) - ($user->unpaid_ticket_count ?? 0) }}</span>
            <span class="font-weight-normal">回</span>
        </span>
    </div>
</div>

<br>

<div class="text-center">
    <a class="btn btn-link text-dark" href="/buy_ticket">≪戻る</a>
    {{-- <a class="btn btn-link text-outline-dark" data-action="pageClose">閉じる</a> --}}
</div>
@endsection
