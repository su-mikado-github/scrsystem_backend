@extends('layouts.dialogs.default')

@section($id . 'dialog-body')
<div class="position-relative" style="height:10em;background-color:#eeeeee;">
    <div class="d-flex justify-content-center align-items-center position-absolute w-100 h-100 top-0 start-0">
        <p class="text-secondary text-center m-0">ファイルをこちらにドラッグするか、<br>クリックしてファイル選択ダイアログで<br>ファイルを指定してください。</p>
    </div>
    <div data-field="target_file" class="position-absolute w-100 h-100 top-0 start-0">
    </div>
</div>
<h6 id="{!! $id !!}_filename" data-field="filename" class="d-none"></h6>
<form id="{!! $id !!}_form" data-field="upload_form" target="{!! $id !!}_response" method="POST" action="{!! route('api.file_upload') !!}" enctype="multipart/form-data">
    <input type="file" data-field="input_file" name="input_file" class="d-none" accept=".csv,text/csv">
</form>
<p id="{!! $id !!}_error" data-field="error" class="text-danger d-none"></p>
@endsection

@section($id . 'dialog-footer')
<div class="d-flex justify-content-center">
    <button type="button" class="col-10 btn scrs-main-button" data-action="ok">{!! $commit_button ?? 'ＯＫ' !!}</button>
</div>
<div class="d-flex justify-content-center">
    <button type="button" class="col-10 btn scrs-sub-button" data-action="cancel" data-bs-dismiss="modal">{!! $cancel_button ?? 'キャンセル' !!}</button>
</div>
@endsection

