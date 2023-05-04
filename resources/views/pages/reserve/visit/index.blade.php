@extends('layouts.default')

@section('page.title')
予約受付
@endsection

@section('main')
<h5 class="text-start">ご来店希望日時を選択してください。</h5>

<div class="row g-0 my-2">
    <div class="col-4 text-center"><span class="mdi mdi-circle-outline scrs-text-available"></span><i class="fa-solid fa-ellipsis px-1"></i>予約可能</div>
    <div class="col-4 text-center"><span class="mdi mdi-triangle-outline scrs-text-few-left"></span><i class="fa-solid fa-ellipsis px-1"></i>残りわずか</div>
    <div class="col-4 text-center"><i class="fa-solid fa-minus scrs-text-fully-occupied"></i><i class="fa-solid fa-ellipsis px-1"></i>売り切れ</div>
</div>

<p><small>※ご予約いただいたお客様につきましても、混雑時はお待ちいただく場合がございます。</small></p>


<div class="row g-0">
    <div class="col-3 text-end fs-4"><i class="fa-solid fa-angles-left"></i></div>
    <div class="col-6 text-center fs-4">99/99～99/99</div>
    <div class="col-3 fs-4"><i class="fa-solid fa-angles-right"></i></div>
</div>

<br>

<div class="d-flex justify-content-center py-2">
    <button data-action="reserve" type="button" class="btn scrs-bg-main-button col-8">予約する</button>
</div>

<div class="d-flex justify-content-center py-2">
    <a class="btn border border-1 scrs-bg-sub-button scrs-border-main col-8" href="/reserve">戻る</a>
</div>

@endsection
