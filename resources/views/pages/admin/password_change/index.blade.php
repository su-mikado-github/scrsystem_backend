@extends('layouts.admin')

@use(Carbon\Carbon)
@use(App\AffiliationDetailTypes)
@use(App\Flags)
@use(App\Weekdays)
@use(App\SortTypes)

<x-script>
import { SCRSPage } from "/scrs-pages.js";
import { SCRSConfirmDialog } from "/dialogs/confirm-dialog.js";

class AdminPasswordChangePage extends SCRSPage {

    constructor() {
        super();
        //
    }
}

SCRSPage.startup(()=>new AdminPasswordChangePage());
</x-script>

@section('page.title')
<span>管理画面<span>≫<span>管理者一覧<span>
@endsection

@section('side-menu')
<x-virtical-menu id="idLeftSideMenu" category="left.side.menu" item-key="admin_users" />
@endsection

@section('main')
<div class="col-5">
    <div class="mb-3 row">
        <div class="col">
            <label for="createStaffLastName" class="form-label">姓<x-signs.require /></label>
            <input id="createStaffLastName" data-field="createStaffLastName" type="text" class="form-control" value="{!! old('last_name') !!}">
            @error('last_name')<p class="text-danger">{{ $message }}</p>@enderror
        </div>
        <div class="col">
            <label for="createStaffFirstName" class="form-label">名<x-signs.require /></label>
            <input id="createStaffFirstName" data-field="createStaffFirstName" type="text" class="form-control" value="{!! old('first_name') !!}">
            @error('first_name')<p class="text-danger">{{ $message }}</p>@enderror
        </div>
    </div>
    <div class="mb-3 row">
        <div class="col">
            <label for="createStaffEmail" class="form-label">メールアドレス<x-signs.require /></label>
            <input id="createStaffEmail" data-field="createStaffEmail" type="email" class="form-control" value="{!! old('email') !!}">
            @error('email')<p class="text-danger">{{ $message }}</p>@enderror
        </div>
    </div>
    <br>
    <div class="mb-3 row">
        <div class="col">
            <label for="password" class="col-form-label">新パスワード<x-signs.require /></label>
            <input id="password" type="password" name="password" data-field="password" class="form-control" placeholder="">
            @error('password')<p class="text-danger">{{ $message }}</p>@enderror
        </div>
        <div class="col">
            <label for="passwordConfirm" class="col-form-label">新パスワード（確認用）<x-signs.require /></label>
            <input id="passwordConfirm" type="password" name="password_confirm" data-field="passwordConfirm" class="form-control" placeholder="">
            @error('password_confirm')<p class="text-danger">{{ $message }}</p>@enderror
        </div>
    </div>
    <br>
    <div class="mb-3 row">
        <div class="col-12 text-end">
            <button data-action="commit" type="button" class="btn scrs-bg-main-button col-4">更新</button>
        </div>
    </div>
</div>
@endsection

{{-- 管理者（スタッフ）削除ダイアログ --}}
<x-confirm-dialog id="update" type="confirm">
    <x-slot name="title">確認</x-slot>
    <h3 data-field="message" class="text-center mb-3">更新してもよろしいですか？</h3>
    <br>
    <x-slot name="ok_button">更新する</x-slot>
    <x-slot name="cancel_button">いいえ</x-slot>
</x-confirm-dialog>
