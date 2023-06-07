@extends('layouts.simple')

@section('page.title')
<span>ログイン<span>
@endsection

@section('main')
<div class="d-flex justify-content-center">
    <div class="col-5">
        <div class="mb-3 row">
            <label for="id_email" class="col-3 col-form-label">メールアドレス<x-signs.require /></label>
            <div class="col-9">
                <input id="id_email" type="email" name="email" class="form-control" placeholder="xxx.yyy@example.com" value="{!! old('email') !!}">
                @error('email')<p class="text-danger">{{ $message }}</p>@enderror
            </div>
        </div>
        <div class="mb-3 row">
            <label for="id_password" class="col-3 col-form-label">パスワード<x-signs.require /></label>
            <div class="col-9">
                <input id="id_password" type="password" name="password" class="form-control" placeholder="*************" value="{!! old('email') !!}">
                @error('password')<p class="text-danger">{{ $message }}</p>@enderror
            </div>
        </div>
        <br>
        <div class="mb-3 row">
            <div class="col-12 text-center">
                <button type="submit" class="btn scrs-bg-main-button col-4">ログイン</button>
            </div>
        </div>
        @error('unauthorized')<div class="alert alert-danger" role="alert">{{ $message }}</div>@enderror
        <br>
        <p class="text-center"><span><x-icon name="fa-solid fa-caret-right" /><a href="{!! route('login.password_reset') !!}" class="text-body">パスワードを忘れた場合はこちら</a></span></p>
    </div>
</div>
@endsection
