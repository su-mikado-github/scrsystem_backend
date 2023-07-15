@use(App\ReserveTypes)
@extends('layouts.default')

@section('page.title')
@if($reserve->type == ReserveTypes::LUNCHBOX)
予約日時の変更完了
@else
予約時間の変更完了
@endif
@endsection

@section('main')
@if($reserve->type == ReserveTypes::LUNCHBOX)
<p>予約日時を変更しました。<br>
右上の×ボタンを押下して画面を閉じてください。</p>
@else
<p>予約時間を変更しました。<br>
右上の×ボタンを押下して画面を閉じてください。</p>
@endif

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
        @if($reserve->type == ReserveTypes::LUNCHBOX)
        <dt class="label">日付／受取時間</dt>
        <dd class="item"><span class="text-nowrap">{{ $reserve->date->format('m月d日') }}</span>&nbsp;<span class="text-nowrap">{{ time_to_hhmm($reserve->time) }}</span></dd>
        <dt class="label">お弁当</dt>
        <dd class="item"><span>{{ $reserve->reserve_count }}</span>個</dd>
        @else
        <dt class="label">日時</dt>
        <dd class="item"><span class="text-nowrap">{{ $reserve->date->format('m月d日') }}</span>&nbsp;<span class="text-nowrap">{{ time_to_hhmm($reserve->time) }}～</span></dd>
        <dt class="label">人数</dt>
        <dd class="item"><span>{{ $reserve->reserve_count }}</span>人</dd>
        @endif
    </dl>
</div>

<br>

<div class="text-center">
    <a class="btn btn-link text-dark" href="/reserve/change/{!! $reserve->date->format('Y-m-d') !!}">≪予約受付に戻る</a>
</div>

@endsection
