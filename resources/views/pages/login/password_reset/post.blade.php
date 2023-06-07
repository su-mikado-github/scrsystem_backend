@extends('layouts.simple')

@section('page.title')
<span>ログイン<span><i class="fa-solid fa-angles-right"></i><span>パスワード・リセット<span>
@endsection

@section('main')
<div class="d-flex justify-content-center">
    <div class="col-5">
        <div class="mb-3 row">
            <div class="col-12 text-center">
                <p>パスワードをリセットしました。<br>
                送信されたメールのリンクからパスワードを再設定してください。</p>
            </div>
        </div>
        <br>
        <div class="mb-3 row">
            <div class="col-12 text-center">
                <a class="btn scrs-sub-button col-4" href="{!! route('login') !!}"><x-icon name="fa-solid fa-angles-left" />ログイン画面へ戻る</a>
            </div>
        </div>
    </div>
</div>
@endsection
