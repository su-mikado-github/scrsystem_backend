@extends('layouts.default')

@section('page.title')
回数券の購入
@endsection

@section('main')
<h5 class="text-center">回数券の残数が無くなりました。</h5>
<h5 class="text-center">ご購入をお願いします。</h5>

<br>

<div class="d-flex justify-content-center py-2">
    <button type="button" class="btn border border-1 scrs-bg-sub-button scrs-border-main col-8">３枚セット</button>
</div>

<div class="d-flex justify-content-center py-2">
    <button type="button" class="btn border border-1 scrs-bg-sub-button scrs-border-main col-8">５枚セット</button>
</div>

<div class="d-flex justify-content-center py-2">
    <button type="button" class="btn border border-1 scrs-bg-sub-button scrs-border-main col-8">１０枚セット</button>
</div>
@endsection
