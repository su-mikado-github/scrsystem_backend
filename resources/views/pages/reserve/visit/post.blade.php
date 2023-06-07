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
@eval(list($hour, $minute, $second) = explode(':', $reserve->time))
<div class="px-5 py-4 scrs-sheet-normal">
    <h3 class="text-center mb-4">ご予約内容</h3>
    <dl class="scrs-item-group mb-0">
        <dt class="label">日時</dt>
        <dd class="item"><span>{{ $reserve->date->format('m月d日') }}</span><span class="px-2"></span><span>{{ sprintf('%02d:%02d', $hour, $minute) }}～</span></dd>
        <dt class="label">人数</dt>
        <dd class="item"><span>{{ $reserve->reserve_count }}</span>人</dd>
    </dl>
</div>

<div class="text-center">
    <a class="btn btn-link text-dark" href="/reserve/visit/{!! $reserve->date->format('Y-m-d') !!}">≪予約受付に戻る</a>
</div>
@endsection
