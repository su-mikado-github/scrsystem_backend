@extends('layouts.admin')

@use(Carbon\Carbon)
@use(App\AffiliationDetailTypes)
@use(App\Flags)
@use(App\Genders)
@use(App\Weekdays)
@use(App\SortTypes)
@use(App\ReserveTypes)

<x-script>
import { SCRSPage } from "/scrs-pages.js";
import { SCRSInformationDialog } from "/dialogs/information-dialog.js";

class AdminUserPage extends SCRSPage {

    #showBuyTicketHistory = null;

    #buyTicketHistoryDialog = null;

    constructor() {
        super();
        //
        this.#showBuyTicketHistory = this.action("showBuyTicketHistory", [ "click" ]);
        this.#buyTicketHistoryDialog = new SCRSInformationDialog(this, "buyTicketHistory", null, [ "ok" ]);
    }

    showBuyTicketHistory_click(e) {
        this.#buyTicketHistoryDialog.open();
    }

    buyTicketHistory_ok(e) {
        this.#buyTicketHistoryDialog.close();
    }
}

SCRSPage.startup(()=>new AdminUserPage());
</x-script>

@section('page.title')
<span>管理画面<span><x-icon name="fa-solid fa-angles-right" /><span>登録者一覧<span><x-icon name="fa-solid fa-angles-right" /><span>詳細<span>
@endsection

@section('side-menu')
<x-virtical-menu id="idLeftSideMenu" category="left.side.menu" item-key="users" />
@endsection

@section('main')
<div class="row">
    <div class="col-8">
        <a class="btn text-decoration-underline" href="{!! route('admin.users') !!}">≪登録者一覧に戻る</a>
    </div>
    <div class="col-4 text-end">
    </div>
</div>

<br>
<div class="row">
    <div class="col-2">
        <div class="p-4">
        @isset(op($target_user->line_user)->profile_picture_url)
        <img src="{!! $target_user->line_user->profile_picture_url !!}" class="w-100">
        @else
        <div class="w-100 text-center">写真無し</div>
        @endisset
        </div>
    </div>
    <div class="col-5 px-2">
        <div class="row g-2 mb-3">
            <div class="col">
                <label class="form-label">姓</label>
                <input type="text" name="last_name" class="form-control-plaintext border bg-white py-2" placeholder="姓" value="{{ $target_user->last_name }}" readonly>
            </div>
            <div class="col">
                <label class="form-label">名</label>
                <input type="text" name="first_name" class="form-control-plaintext border bg-white py-2" placeholder="名" value="{{ $target_user->first_name }}" readonly>
            </div>
        </div>

        <div class="row g-2 mb-3">
            <div class="col">
                <label class="form-label">せい</label>
                <input type="text" name="last_name_kana" class="form-control-plaintext border bg-white py-2" placeholder="せい" value="{{ $target_user->last_name_kana }}" readonly>
            </div>
            <div class="col">
                <label class="form-label">めい</label>
                <input type="text" name="first_name_kana" class="form-control-plaintext border bg-white py-2" placeholder="めい" value="{{ $target_user->first_name_kana }}" readonly>
            </div>
        </div>

        <div class="row g-2 mb-3">
            <div class="col-2">
                <label class="form-label">性別</label>
                <input type="text" name="sex" class="form-control-plaintext border bg-white py-2" value="{{ op(Genders::of($target_user->sex))->ja ?? '（不明）' }}" readonly>
            </div>
        </div>

        <div class="row g-2 mb-3">
            <div class="col-4">
                <label class="form-label">誕生日</label>
                <input type="text" name="birthday" class="form-control-plaintext border bg-white py-2" value="{{ op($target_user->birthday)->format('Y/m/d') ?? '' }}" readonly>
            </div>
        </div>

        <div class="row g-2 mb-3">
            <div class="col-6">
                <label class="form-label">所属</label>
                <input type="text" name="affiliation" class="form-control-plaintext border bg-white py-2" value="{{ op($target_user->affiliation)->name }}" readonly>
            </div>

            <div class="col-6">
                <label class="form-label">&nbsp;</label>
                <input type="text" name="affiliation_detail" class="form-control-plaintext border bg-white py-2" value="{{ op($target_user->affiliation_detail)->name }}" readonly>
            </div>
        </div>

        @if(op($target_user->affiliation)->detail_type == AffiliationDetailTypes::INTERNAL)
        <div class="row g-2 mb-3">
            <div class="col-6">
                <label class="form-label">学年</label>
                <input type="text" name="school_year" class="form-control-plaintext border bg-white py-2" value="{{ op($target_user->school_year)->name }}" readonly>
            </div>
        </div>
        @elseif(op($target_user->affiliation)->detail_type == AffiliationDetailTypes::EXTERNAL)
        @endif

        <div class="row g-2 mb-3">
            <div class="col-4">
                <label class="form-label">電話番号</label>
                <input type="text" name="telephone_no" class="form-control-plaintext border bg-white py-2" value="{{ $target_user->telephone_no }}" readonly>
            </div>
        </div>

        <div class="row g-2 mb-3">
            <div class="col">
                <label class="form-label">メールアドレス</label>
                <input type="text" name="email" class="form-control-plaintext border bg-white py-2" value="{{ $target_user->email }}" readonly>
            </div>
        </div>

        <div class="row g-2 mb-3">
            <div class="col-3">
                <label class="form-label">食券残数</label>
                <div class="input-group">
                    <input type="text" name="valid_ticket_count" class="form-control border bg-white py-2 text-end" value="{{ $target_user->lastTicketCount }}" readonly>
                    <span class="input-group-text bg-transparent border-top-0 border-bottom-0 border-end-0">枚</span>
                </div>
            </div>
            <div class="col-3">
                <label class="form-label">&nbsp;</label>
                <button data-action="showBuyTicketHistory" class="btn scrs-sub-button form-control" type="button">食券購入履歴</button>
            </div>
        </div>
    </div>
    <div class="col-5">
        <h5 class="d-flex">利用履歴</h5>
        <small class="d-block text-end">
            <span class="text-nowrap fs-6">○<x-icon name="fa-solid fa-ellipsis" />予約あり</span>
            &nbsp;
            <span class="text-nowrap fs-6">●<x-icon name="fa-solid fa-ellipsis" />チェックイン／受取</span>
            &nbsp;
            <span class="text-nowrap fs-6">×<x-icon name="fa-solid fa-ellipsis" />事前キャンセル</span>
            &nbsp;
            <span class="text-nowrap fs-6">－<x-icon name="fa-solid fa-ellipsis" />当日キャンセル</span>
        </small>
        <table class="table table-bordered">
        <colgroup>
            <col style="width:10em;">
            <col style="width:5em;">
            <col style="width:8em;">
            <col style="width:8em;">
            <col>
        </colgroup>
        <thead class="scrs-bg-main">
            <tr>
                <th>予約日</th>
                <th>時間</th>
                <th>食堂</th>
                <th>弁当</th>
                <th>食券</th>
            </tr>
        </thead>
        <tbody class="bg-white">
            @foreach($target_user->reserves()->dateOrdered()->timeOrdered()->typeOrdered()->get() as $reserve)
            @php
                $calendar = $reserve->calendar;
                $date = sprintf('%s (%s)', $calendar->date->format('Y/m/d'), Weekdays::of($calendar->weekday)->ja);
                $visit_status = null;
                $visit_count = null;
                $lunchbox_status = null;
                $lunchbox_count = null;
                if ($reserve->type == ReserveTypes::LUNCHBOX) {
                    $lunchbox_count = $reserve->reserve_count;
                    if ($reserve->is_through == Flags::ON) {
                        $lunchbox_status = '－';
                    }
                    else if (isset($reserve->cancel_dt)) {
                        $lunchbox_status = '×';
                    }
                    else if (isset($reserve->checkin_dt)) {
                        $lunchbox_status = '●';
                    }
                    else {
                        $lunchbox_status = '○';
                    }
                }
                else {
                    $visit_count = $reserve->reserve_count;
                    if ($reserve->is_through == Flags::ON) {
                        $visit_status = '－';
                    }
                    else if (isset($reserve->cancel_dt)) {
                        $visit_status = '×';
                    }
                    else if (isset($reserve->checkin_dt)) {
                        $visit_status = '●';
                    }
                    else {
                        $visit_status = '○';
                    }
                }
                @endphp
            <tr>
                <td class="text-center">{{ $date }}</td>
                <td class="text-center">{{ time_to_hhmm($reserve->time) ?? ' ' }}</td>
                <td class="text-center">@isset($visit_status){{ $visit_status }}&nbsp;({{ $visit_count }}人)@else &nbsp; @endisset</td>
                <td class="text-center">@isset($lunchbox_status){{ $lunchbox_status }}&nbsp;({{ $lunchbox_count }}個)@else &nbsp; @endisset</td>
                <td class="text-end">@isset($reserve->cancel_dt) &nbsp; @else{{ $reserve->reserve_count }}枚 @endisset</td>
            </tr>
            @endforeach
        </tbody>
        </table>
    </div>
</div>
@endsection

{{-- 食券購入履歴 --}}
<x-dialogs.information id="buyTicketHistory">
    <x-slot name="title">食券購入履歴</x-slot>
    <div class="py-2">
        <div style="max-height:640px;overflow-y:auto;">
        <table class="table table-bordered table-hover m-0 position-relative">
            <thead class="scrs-bg-main position-sticky top-0">
                <tr>
                    <th>購入日付</th>
                    <th style="width:5em;">時間</th>
                    <th style="width:6em;">購入枚数</th>
                    <th style="width:6em;">残枚数</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($target_user->buy_tickets()->enabled()->get() as $buy_ticket)
                <tr>
                    <td>{{ $buy_ticket->buy_dt->format('Y/m/d') }}({{ Weekdays::fromDate($buy_ticket->buy_dt)->ja }})</td>
                    <td class="text-center">{{ $buy_ticket->buy_dt->format('H:i') }}</td>
                    <td class="text-end">{{ $buy_ticket->ticket_count }}&nbsp;&nbsp;</td>
                    <td class="text-end">{{ $buy_ticket->valid_ticket_count }}&nbsp;&nbsp;</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
    <x-slot name="ok_button">閉じる</x-slot>
</x-dialogs.information>
