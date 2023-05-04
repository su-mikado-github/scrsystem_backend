@extends('layouts.default')

<x-script id="reserve">
</x-script>

@section('page.title')
ご予約内容
@endsection

@section('main')
<h5 class="text-center">ご予約内容を選択してください。</h5>

<br>

<div class="d-flex justify-content-center py-2">
    <a data-action="visit" class="btn scrs-bg-main-button col-8" href="/reserve/visit">来店予約</a>
</div>

<div class="d-flex justify-content-center py-2">
    <a data-action="lunchbox" class="btn border border-1 scrs-bg-sub-button scrs-border-main col-8" href="/reserve/lunchbox">お弁当予約</a>
</div>

@endsection
