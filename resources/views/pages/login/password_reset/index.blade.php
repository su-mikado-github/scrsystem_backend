@extends('layouts.simple')

<x-script>
import { SCRSPage } from "/scrs-pages.js";
import { SCRSConfirmDialog } from "/dialogs/confirm-dialog.js";

class PasswordResetPage extends SCRSPage {
    #reset = null;

    constructor() {
        super();
        //
        this.#reset = this.action("reset", [ "click" ]);
    }

    reset_click(e) {
        this.patch("/login/password_reset");
    }
}

SCRSPage.startup(()=>new PasswordResetPage());
</x-script>

@section('page.title')
<span>ログイン<span><x-icon name="fa-solid fa-angles-right" /><span>パスワード・リセット<span>
@endsection

@section('main')
<div class="d-flex justify-content-center">
    <div class="col-5">
        <div class="mb-3 row">
            <label for="email" class="col-3 col-form-label">メールアドレス<x-signs.require /></label>
            <div class="col-9">
                <input id="email" type="email" name="email" data-field="email" class="form-control" placeholder="xxx.yyy@example.com">
                @error('email')<p class="text-danger">{{ $message }}</p>@enderror
            </div>
        </div>
        <br>
        <div class="mb-3 row">
            <div class="col-12 text-center">
                <button data-action="reset" type="button" class="btn scrs-bg-main-button col-4">パスワードをリセット</button>
            </div>
        </div>
    </div>
</div>
@endsection
