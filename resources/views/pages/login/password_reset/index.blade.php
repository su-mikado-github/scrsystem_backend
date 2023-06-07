@extends('layouts.simple')

@section('page.title')
<span>ログイン<span><x-icon name="fa-solid fa-angles-right" /><span>パスワード・リセット<span>
@endsection

@section('main')
<div class="d-flex justify-content-center">
    <div class="col-5">
        <div class="mb-3 row">
            <label for="id_email" class="col-3 col-form-label">メールアドレス<x-signs.require /></label>
            <div class="col-9">
                <input id="id_email" type="email" name="email" class="form-control" placeholder="xxx.yyy@example.com">
            </div>
        </div>
        <br>
        <div class="mb-3 row">
            <div class="col-12 text-center">
                <button type="submit" class="btn scrs-bg-main-button col-4">パスワードをリセット</button>
            </div>
        </div>
    </div>
</div>
@endsection
