@extends('layouts.simple')

<x-script>
import { SCRSPage } from "/scrs-pages.js";
import { SCRSConfirmDialog } from "/dialogs/confirm-dialog.js";

class NewPasswordPage extends SCRSPage {
    #commit = null;

    constructor() {
        super();
        //
        this.#commit = this.action("commit", [ "click" ]);
    }

    commit_click(e) {
        this.patch([ "/new_password", @json($staff->id), @json($reset_token) ]);
    }
}

SCRSPage.startup(()=>new NewPasswordPage());
</x-script>

@section('page.title')
<span>ログイン<span><x-icon name="fa-solid fa-angles-right" /><span>パスワード再設定<span>
@endsection

@section('main')
<div class="d-flex justify-content-center">
    <div class="col-5">
        <div class="mb-3 row">
            <label for="password" class="col-3 col-form-label">新パスワード<x-signs.require /></label>
            <div class="col-9">
                <input id="password" type="password" name="password" data-field="password" class="form-control" placeholder="">
                @error('password')<p class="text-danger">{{ $message }}</p>@enderror
            </div>
        </div>
        <div class="mb-3 row">
            <label for="passwordConfirm" class="col-3 col-form-label">新パスワード（確認用）<x-signs.require /></label>
            <div class="col-9">
                <input id="passwordConfirm" type="password" name="password_confirm" data-field="passwordConfirm" class="form-control" placeholder="">
                @error('password_confirm')<p class="text-danger">{{ $message }}</p>@enderror
            </div>
        </div>
        <br>
        <div class="mb-3 row">
            <div class="col-12 text-center">
                <button data-action="commit" type="button" class="btn scrs-bg-main-button col-4">パスワード更新</button>
            </div>
        </div>
    </div>
</div>
@endsection
