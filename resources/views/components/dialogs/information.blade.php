@extends('layouts.dialogs.default')

@section($id . 'dialog-body')
{{ $slot }}
@endsection

@section($id . 'dialog-footer')
<div class="d-flex justify-content-center">
    <button type="button" class="col-10 btn scrs-bg-main-button" data-action="ok">{!! $ok_button ?? 'ＯＫ' !!}</button>
</div>
@endsection

