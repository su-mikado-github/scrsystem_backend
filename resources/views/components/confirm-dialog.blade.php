@extends('layouts.dialogs.default')

@section($id . 'dialog-body')
{!! $slot !!}
@endsection

@section($id . 'dialog-footer')
@isset($footer)
{{ $footer }}
@else
<div class="d-flex justify-content-center">
    <button type="button" class="col-10 btn scrs-bg-main-button" data-action="ok">{!! $ok_button ?? 'ＯＫ' !!}</button>
</div>
<div class="d-flex justify-content-center">
    <button type="button" class="col-10 btn scrs-bg-sub-button scrs-border-main" data-action="cancel" data-bs-dismiss="modal">{!! $cancel_button ?? 'キャンセル' !!}</button>
</div>
@endif
@endsection

