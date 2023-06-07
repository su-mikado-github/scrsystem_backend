@extends('layouts.dialogs.default')

@section($id . 'dialog-body')


<x-slot name="title">確認</x-slot>
<h3 data-name="message" class="text-center mb-3">1月8日(土)　09:50～</h3>
<p data-name="description" class="text-center">※混雑時はお待ちいただく場合がございます</p>
<x-slot name="ok_button">予約を確定する</x-slot>
<x-slot name="cancel_button">戻る</x-slot>


@endsection

@section($id . 'dialog-footer')
<div class="d-flex justify-content-center">
    <button type="button" class="col-10 btn scrs-bg-main-button" data-action="ok">{!! $ok_button ?? 'ＯＫ' !!}</button>
</div>
<div class="d-flex justify-content-center">
    <button type="button" class="col-10 btn scrs-bg-sub-button scrs-border-main" data-action="cancel" data-bs-dismiss="modal">{!! $cancel_button ?? 'キャンセル' !!}</button>
</div>
@endsection
