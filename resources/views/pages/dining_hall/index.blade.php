@extends('layouts.dining_hall')

<x-script>
import { SCRSPage } from "/scrs-pages.js";
import { SCRSConfirmDialog } from "/dialogs/confirm-dialog.js";

class DiningHallPage extends SCRSPage {

    #payments = null;

    #paymentConfitmDialog = null;

    #reloadSuspend = true;

    #refresh() {
        if (!this.#reloadSuspend) {
            this.reload();
        }
        setTimeout(()=>this.#refresh(), 5000);
    }

    constructor() {
        super();

        this.#payments = this.actions("payment", [ "click" ]);

        this.#paymentConfitmDialog = new SCRSConfirmDialog(this, "payment", null, [ "ok", "cancel" ]);

        this.#refresh();
        this.#reloadSuspend = false;
    }

    payment_click(e) {
        this.#reloadSuspend = true;
        const buyTicketId = e.currentTarget.dataset.id;
        this.#paymentConfitmDialog.open({ buyTicketId });
    }

    payment_ok(e) {
        const params = e.detail;
        this.patch([ "/dining_hall", @json($today->format('Y-m-d')), "buy_tickets", params.buyTicketId, "payment" ]);
    }

    payment_cancel(e) {
        this.#reloadSuspend = false;
        this.#paymentConfitmDialog.close();
    }
}

SCRSPage.startup(()=>new DiningHallPage());
</x-script>



@section('main')
<div class="px-3 py-4 scrs-sheet-normal">
    <h3>食券購入（支払い）<small class="text-danger" style="font-size:70%;">※支払い手続きは名前をタップしてください。</small></h3>
    <div class="row gx-3">
        @foreach($buy_tickets as $buy_ticket)
        <div class="col-6">
            <button type="button" class="w-100 rounded-3 btn border scrs-border-main" data-action="payment" data-id="{!! $buy_ticket->id !!}">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle" style="width:64px;height:64px;">
                        @isset(op($buy_ticket->user->line_user)->profile_picture_url)
                        <img src="{!! $buy_ticket->user->line_user->profile_picture_url !!}" class="w-100 rounded-circle">
                        @else
                        <x-icon name="fa-solid fa-circle-user" class="text-body" style="font-size:64px;margin:0!important;cursor:pointer;" />
                        @endisset
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3 text-body">
                        <div>
                            <span class="text-nowrap h6">{{ $buy_ticket->user->last_name }} {{ $buy_ticket->user->first_name }}</span>
                            <span class="text-nowrap">（購入日時：{{ $buy_ticket->buy_dt->format('n/j G:i') }}）</span>
                        </div>
                        <div>
                            <span class="text-nowrap h3">{{ $buy_ticket->ticket_count }}枚セット</span>
                        </div>
                    </div>
                </div>
            </button>
        </div>
        @endforeach
    </div>
</div>

<br>
<div class="px-3 py-4 scrs-sheet-normal">
    <h3 class="text-center mb-4">チェックイン状況</h3>
    <div class="row gx-3">
    @foreach($dining_hall_reserves as $reserve)
        <div class="col-6">
            @eval($reserve_user = $reserve->user)
            @eval($is_checkin = isset($reserve->checkin_dt))
            <div class="text-nowrap text-center">
                <div class="border {!! $is_checkin ? 'scrs-bg-main' : 'scrs-border-main' !!} py-4">
                    <ruby>
                        <rb class="display-6 {!! $is_checkin ? '' : 'text-body' !!} fw-bold me-2">{{ $reserve_user->last_name }}</rb>
                        <rt class="{!! $is_checkin ? '' : 'text-body' !!} pb-2">{{ $reserve_user->last_name_kana }}</rt>
                    </ruby>
                    <ruby>
                        <rb class="display-6 {!! $is_checkin ? '' : 'text-body' !!} fw-bold">{{ $reserve_user->first_name }}</rb>
                        <rt class="{!! $is_checkin ? '' : 'text-body' !!} pb-2">{{ $reserve_user->first_name_kana }}</rt>
                    </ruby>
                </div>
            </div>
        </div>
    @endforeach
    </div>
</div>

<br>
<div class="px-3 py-4 scrs-sheet-normal">
    <h3 class="text-center mb-4">お弁当の受け取り状況</h3>
    <div class="row gx-3">
    @foreach($lunchbox_reserves as $reserve)
        <div class="col-6">
            @eval($reserve_user = $reserve->user)
            @eval($is_checkin = isset($reserve->checkin_dt))
            <div class="text-nowrap text-center">
                <div class="border {!! $is_checkin ? 'scrs-bg-main' : 'scrs-border-main' !!} py-4">
                    <ruby>
                        <rb class="display-6 {!! $is_checkin ? '' : 'text-body' !!} fw-bold mt-2 me-2">{{ $reserve_user->last_name }}</rb>
                        <rt class="{!! $is_checkin ? '' : 'text-body' !!} pb-2">{{ $reserve_user->last_name_kana }}</rt>
                    </ruby>
                    <ruby>
                        <rb class="display-6 {!! $is_checkin ? '' : 'text-body' !!} fw-bold">{{ $reserve_user->first_name }}</rb>
                        <rt class="{!! $is_checkin ? '' : 'text-body' !!} pb-2">{{ $reserve_user->first_name_kana }}</rt>
                    </ruby>
                </div>
            </div>
        </div>
    @endforeach
    </div>
</div>

@endsection

{{--  --}}
<x-confirm-dialog id="payment" type="confirm">
    <x-slot name="title">確認</x-slot>
    <h3 data-field="message" class="text-center mb-3">支払いを完了として、よろしいですか？</h3>
    <br>
    <x-slot name="footer">
        <div class="row gx-3">
            <div class="col-6">
                <button type="button" class="btn btn-lg scrs-bg-main-button w-100" data-action="ok">支払い完了</button>
            </div>
            <div class="col-6">
                <button type="button" class="btn btn-lg scrs-bg-sub-button scrs-border-main w-100" data-action="cancel">キャンセル</button>
            </div>
        </div>
    </x-slot>
</x-confirm-dialog>
