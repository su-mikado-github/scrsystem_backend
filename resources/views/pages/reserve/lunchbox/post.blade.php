@extends('layouts.default')

{{-- <x-script>
import { SCRSPage } from "/scrs-pages.js";

class ReserveVisitPostPage extends SCRSPage {
    constructor() {
        super();
        //
    }
}

SCRSPage.startup(()=>new ReserveVisitPostPage());
</x-script> --}}

@section('page.title')
予約完了
@endsection

@section('main')
<p>下記のご予約内容で承りました。<br>
右上の×ボタンを押下して画面を閉じてください。</p>

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

<div class="px-5 py-4 scrs-sheet-normal">
    <h3 class="text-center mb-4">ご予約内容</h3>
    <dl class="scrs-item-group mb-0">
        <dt class="label">日付／受取時間</dt>
        <dd class="item"><span class="text-nowrap">{{ $reserve->date->format('m月d日') }}</span>&nbsp;<span class="text-nowrap">{{ time_to_hhmm($reserve->time) }}</span></dd>
        <dt class="label">お弁当</dt>
        <dd class="item"><span>{{ $reserve->reserve_count }}</span>個</dd>
    </dl>
</div>

<br>

<div class="text-center">
    <a class="btn btn-link text-dark" href="/reserve/lunchbox/{!! $reserve->date->format('Y-m-d') !!}">≪予約受付に戻る</a>
</div>
@endsection
