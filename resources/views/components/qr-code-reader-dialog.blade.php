@extends('layouts.dialogs.default')

@section('dialog-body')
<br>
<canvas data-action="camera" data-field="camera" class="w-100" hidden></canvas>
<div class="scrs-sheet-normal">
    <p>QRコードに合わせると赤枠で囲います。<br>囲われたらをタップしてください。</p>
</div>
@endsection

@section('dialog-footer')
<div class="d-flex justify-content-center">
    <button type="button" class="col-10 btn scrs-bg-sub-button scrs-border-main" data-action="cancel" data-bs-dismiss="modal">{!! $cancel_button ?? 'キャンセル' !!}</button>
</div>
@endsection

