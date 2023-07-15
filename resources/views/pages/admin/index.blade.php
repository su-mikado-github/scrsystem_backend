@extends('layouts.admin')

@use(App\Weekdays)
@use(App\AffiliationDetailTypes)

<x-script>
    import { SCRSPage } from "/scrs-pages.js";

    class AdminPage extends SCRSPage {
        #autoReload = null;

        #intervalId = null;
        #reloadTimer = 0;

        #reload() {
            const checked = this.#autoReload.checked;
            if (checked) {
                if (this.#reloadTimer >= 60) {
                    @isset($date)
                    this.forward([ "/admin", @json($date) ]);
                    @else
                    this.forward([ "/admin" ]);
                    @endisset
                    clearInterval(this.#intervalId);
                    this.#intervalId = null;
                }
                else {
                    this.#reloadTimer ++;
                    console.log("タイマー: "+this.#reloadTimer);
                }
            }
        }

        constructor() {
            super();
            //
            this.#autoReload = this.action("autoReload", [ "click" ]);
            this.#autoReload.checked = (JSON.parse(window.localStorage.autoReload||null) == true);

            this.#intervalId = setInterval(()=>this.#reload(), 1000);
        }

        autoReload_click(e) {
            this.#reloadTimer = 0;
            const checked = this.#autoReload.checked;
            window.localStorage.autoReload = checked;
        }
    }

    SCRSPage.startup(()=>new AdminPage());
    </x-script>

@section('page.title')
<span>管理画面<span>
@endsection

@section('side-menu')
<x-virtical-menu id="idLeftSideMenu" category="left.side.menu" item-key="root" />
@endsection

@section('main')
<div class="row">
    <div class="col-4 text-center">
        &nbsp;
    </div>
    <div class="col-4 text-center">
        <span class="text-nowrap3">{{ $calendar->date->format('Y年m月d日') }}({{ Weekdays::fromDate($calendar->date)->ja }})&nbsp;{{ now()->format('H:i') }}</span>
    </div>
    <div class="col-4 text-end">
        <div class="d-inline-block">
            <div class="form-check">
                <input id="autoReload" type="checkbox" data-action="autoReload" class="form-check-input" value="">
                <label for="autoReload" class="form-check-label">自動的に画面をリフレッシュする（１分毎）</label>
            </div>
        </div>
    </div>
</div>

<br>
<div class="row">
    <div class="col-6">
        <div class="card shadow-none border-0 bg-transparent">
            <div class="card-header border-0 bg-transparent p-0">
                <h6>食堂予約</h6>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered table-hover">
                <colgroup>
                    <col style="width:9em;">
                    <col>
                    <col style="width:6em;">
                    <col style="width:4em;">
                    <col style="width:4em;">
                    <col style="width:8em;">
                </colgroup>
                <thead class="scrs-bg-main">
                    <tr>
                        <th>氏名</th>
                        <th>所属</th>
                        <th>学年</th>
                        <th>時間</th>
                        <th>人数</th>
                        <th>状態</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach($visit_reserves as $reserve)
                    @eval($time = transform($reserve->time, function($value) {  list($h,$m,$s) = explode(':', $value); return sprintf('%02d:%02d', $h, $m); }))
                    <tr>
                        <td class="">{{ $reserve->user->last_name }} {{ $reserve->user->first_name }}</td>
                        <td>
                            <div class="d-flex flex-row">
                                <div class="col-6 text-start">{{ $reserve->user->affiliation->name }}</div>
                                <div class="col-6 text-start">{{ $reserve->user->affiliation_detail->name }}</div>
                            </div>
                        </td>
                        <td class="text-center">@if($reserve->user->affiliation->detail_type == AffiliationDetailTypes::INTERNAL){{ $reserve->user->school_year->name }}@else &nbsp; @endif</td>
                        <td class="text-center">{{ $time ?? ' ' }}</td>
                        <td class="text-end">{{ $reserve->reserve_count }}人</td>
                        <td class="text-center">@if(isset($reserve->cancel_dt))取消 @elseif(isset($reserve->checkin_dt))来店済 @else 予約中 @endif</td>
                    </tr>
                    @endforeach
                </tbody>
                @if($visit_reserves->count() == 0)
                <tfoot>
                    <tr class="border-0">
                        <td colspan="5" class="border-0">※本日の予約はありません。</td>
                    </tr>
                </tfoot>
                @endif
                </table>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card shadow-none border-0 bg-transparent">
            <div class="card-header border-0 bg-transparent p-0">
                <h6>お弁当予約</h6>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered table-hover">
                <colgroup>
                    <col style="width:10em;">
                    <col>
                    <col style="width:6em;">
                    <col style="width:4em;">
                    <col style="width:4em;">
                    <col style="width:8em;">
                </colgroup>
                <thead class="scrs-bg-main">
                    <tr>
                        <th>氏名</th>
                        <th>所属</th>
                        <th>学年</th>
                        <th>時間</th>
                        <th>個数</th>
                        <th>状態</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach($lunchbox_reserves as $reserve)
                    @eval($time = transform($reserve->time, function($value) {  list($h,$m,$s) = explode(':', $value); return sprintf('%02d:%02d', $h, $m); }))
                    <tr>
                        <td class="">{{ $reserve->user->last_name }} {{ $reserve->user->first_name }}</td>
                        <td>
                            <div class="d-flex flex-row">
                                <div class="col-6 text-start">{{ $reserve->user->affiliation->name }}</div>
                                <div class="col-6 text-start">{{ $reserve->user->affiliation_detail->name }}</div>
                            </div>
                        </td>
                        <td class="text-center">@if($reserve->user->affiliation->detail_type == AffiliationDetailTypes::INTERNAL){{ $reserve->user->school_year->name }}@else &nbsp; @endif</td>
                        <td class="text-center">{{ time_to_hhmm($reserve->time) }}</td>
                        <td class="text-end">{{ $reserve->reserve_count }}</td>
                        <td class="text-center">@if(isset($reserve->cancel_dt))取消 @elseif(isset($reserve->checkin_dt))受取済 @else 予約中 @endif</td>
                    </tr>
                    @endforeach
                </tbody>
                @if($lunchbox_reserves->count() == 0)
                <tfoot>
                    <tr class="border-0">
                        <td colspan="5" class="border-0">※本日の予約はありません。</td>
                    </tr>
                </tfoot>
                @endif
                </table>
            </div>
        </div>
    </div>
    {{-- <div class="col-4">
        <div class="card shadow-none border-0 bg-transparent">
            <div class="card-header border-0 bg-transparent p-0">
                <h6>食券購入</h6>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered table-hover">
                <thead class="scrs-bg-main">
                    <tr>
                        <th>氏名</th>
                        <th>所属</th>
                        <th>枚数</th>
                        <th>支払</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                </tbody>
                </table>
            </div>
        </div>
    </div> --}}
</div>

@endsection
