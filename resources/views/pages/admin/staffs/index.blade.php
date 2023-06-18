@extends('layouts.admin')

@use(Carbon\Carbon)
@use(App\AffiliationDetailTypes)
@use(App\Flags)
@use(App\Weekdays)
@use(App\SortTypes)

<x-script>
import { SCRSPage } from "/scrs-pages.js";
import { SCRSConfirmDialog } from "/dialogs/confirm-dialog.js";

class AdminUsersPage extends SCRSPage {

    #lastName = null;
    #firstName = null;
    #email = null;
    #isPasswordReset = null;

    #create = null;
    #modify = null;
    #remove = null;

    #createStaffConfirmDialog = null;
    #createStaffLastName = null;
    #createStaffFirstName = null;
    #createStaffEmail = null;

    #modifyStaffConfirmDialog = null;
    #modifyStaffLastName = null;
    #modifyStaffFirstName = null;
    #modifyStaffEmail = null;
    #modityStaffPasswordReset = null;

    #removeStaffConfirmDialog = null;
    #removeStaffLastName = null;
    #removeStaffFirstName = null;
    #removeStaffEmail = null;

    constructor() {
        super();
        //
        this.#lastName = this.field("lastName");
        this.#firstName = this.field("firstName");
        this.#email = this.field("email");
        this.#isPasswordReset = this.field("isPasswordReset");

        this.#create = this.action("create", [ "click" ]);
        this.#modify = this.actions("modify", [ "click" ]);
        this.#remove = this.actions("remove", [ "click" ]);

        this.#createStaffConfirmDialog = new SCRSConfirmDialog(this, "createStaffConfirm", null, [ "ok" ]);
        this.#createStaffLastName = this.#createStaffConfirmDialog.field("createStaffLastName");
        this.#createStaffFirstName = this.#createStaffConfirmDialog.field("createStaffFirstName");
        this.#createStaffEmail = this.#createStaffConfirmDialog.field("createStaffEmail");

        this.#modifyStaffConfirmDialog = new SCRSConfirmDialog(this, "modifyStaffConfirm", null, [ "ok" ]);
        this.#modifyStaffLastName = this.#modifyStaffConfirmDialog.field("modifyStaffLastName");
        this.#modifyStaffFirstName = this.#modifyStaffConfirmDialog.field("modifyStaffFirstName");
        this.#modifyStaffEmail = this.#modifyStaffConfirmDialog.field("modifyStaffEmail");
        this.#modityStaffPasswordReset = this.#modifyStaffConfirmDialog.field("modityStaffPasswordReset");

        this.#removeStaffConfirmDialog = new SCRSConfirmDialog(this, "removeStaffConfirm", null, [ "ok" ]);
        this.#removeStaffLastName = this.#removeStaffConfirmDialog.field("removeStaffLastName");
        this.#removeStaffFirstName = this.#removeStaffConfirmDialog.field("removeStaffFirstName");
        this.#removeStaffEmail = this.#removeStaffConfirmDialog.field("removeStaffEmail");

        @if(session('method') == 'post')
        this.#createStaffConfirmDialog.open();
        @elseif(session('method') == 'put')
        this.#modifyStaffConfirmDialog.open({ id: @json(session('id')) });
        @endif
    }

    create_click(e) {
        this.#createStaffLastName.value = '';
        this.#createStaffFirstName.value = '';
        this.#createStaffEmail.value = '';
        this.#createStaffConfirmDialog.open();
    }

    createStaffConfirm_ok(e) {
        this.#lastName.value = this.#createStaffLastName.value;
        this.#firstName.value = this.#createStaffFirstName.value;
        this.#email.value = this.#createStaffEmail.value;
        this.post("/admin/staffs");
    }

    modify_click(e) {
        const id = e.target.dataset["id"];
        this.#modifyStaffLastName.value = e.target.dataset["last_name"] || '';
        this.#modifyStaffFirstName.value = e.target.dataset["first_name"] || '';
        this.#modifyStaffEmail.value = e.target.dataset["email"] || '';
        this.#modityStaffPasswordReset.checked = false;
        this.#modifyStaffConfirmDialog.open({ id });
    }

    modifyStaffConfirm_ok(e) {
        const { id } = e.detail;
        this.#lastName.value = this.#modifyStaffLastName.value;
        this.#firstName.value = this.#modifyStaffFirstName.value;
        this.#email.value = this.#modifyStaffEmail.value;
        this.#isPasswordReset.value = (this.#modityStaffPasswordReset.checked ? Flags.ON.id : Flags.OFF.id);
        this.put([ "/admin/staffs", id ]);
    }

    remove_click(e) {
        const id = e.target.dataset["id"];
        this.#removeStaffLastName.value = e.target.dataset["last_name"] || '';
        this.#removeStaffFirstName.value = e.target.dataset["first_name"] || '';
        this.#removeStaffEmail.value = e.target.dataset["email"] || '';
        this.#removeStaffConfirmDialog.open({ id });
    }

    removeStaffConfirm_ok(e) {
        const { id } = e.detail;
        this.delete([ "/admin/staffs", id ]);
    }
}

SCRSPage.startup(()=>new AdminUsersPage());
</x-script>

@section('page.title')
<span>管理画面<span><x-icon name="fa-solid fa-angles-right" /><span>管理者一覧<span>
@endsection

@section('side-menu')
<x-virtical-menu id="idLeftSideMenu" category="left.side.menu" item-key="admin_users" />
@endsection

@section('main')
<div class="row">
    <div class="col-8">

    </div>
    <div class="col-4 text-end">
        <button data-action="create" type="button" class="btn scrs-main-button" style="width:8em;">新規登録</button>
    </div>
</div>

<br>
<input type="hidden" data-field="lastName" name="last_name">
<input type="hidden" data-field="firstName" name="first_name">
<input type="hidden" data-field="email" name="email">
<input type="hidden" data-field="isPasswordReset" name="is_password_reset">

<table class="table table-bordered table-hover">
<thead class="scrs-bg-main">
    <tr>
        <th style="width:10em;">
            <div class="d-flex">
                <div class="flex-grow-1">氏名</div>
            </div>
        </th>
        <th>メールアドレス</th>
        <th style="width:5em;">&nbsp;</th>
    </tr>
</thead>
<tbody>
    @foreach($staffs as $staff)
    <tr class="bg-white">
        <td>{{ $staff->last_name ?? ' ' }} {{ $staff->first_name ?? ' ' }}</td>
        <td>{{ $staff->email ?? ' ' }}</td>
        <td class="px-0" style="width:5em;">
            <div class="d-flex flex-row">
                <div class="col-6 text-center"><x-icon name="fa-solid fa-magnifying-glass" style="font-size:22px;margin:0!important;cursor:pointer;" data-action="modify" data-id="{!! $staff->id !!}"
                    data-first_name="{{ $staff->first_name }}" data-last_name="{{ $staff->last_name }}" data-email="{{ $staff->email }}" /></div>
                @if($staff->id == 1)
                <div class="col-6 text-center">&nbsp;</div>
                @else
                <div class="col-6 text-center"><x-icon name="fa-solid fa-trash-can" class="text-danger" style="font-size:22px;margin:0!important;cursor:pointer;" data-action="remove" data-id="{!! $staff->id !!}"
                    data-first_name="{{ $staff->first_name }}" data-last_name="{{ $staff->last_name }}" data-email="{{ $staff->email }}" /></div>
                @endif
            </div>
        </td>
    </tr>
    @endforeach
</tbody>
<tfoot>
</tfoot>
</table>
@endsection

{{-- 管理者（スタッフ）新規登録ダイアログ --}}
<x-confirm-dialog id="createStaffConfirm" type="confirm">
    <x-slot name="title">新規登録</x-slot>
    <div class="row">
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
    <div class="row">
        <label for="createStaffEmail" class="form-label">メールアドレス<x-signs.require /></label>
        <input id="createStaffEmail" data-field="createStaffEmail" type="email" class="form-control" value="{!! old('email') !!}">
        @error('email')<p class="text-danger">{{ $message }}</p>@enderror
    </div>
    <br>
    <p class="text-start text-danger">※パスワードは、自動生成し上記メールアドレスに通知します。</p>
    <x-slot name="ok_button">追加する</x-slot>
    <x-slot name="cancel_button">いいえ</x-slot>
</x-confirm-dialog>

{{-- 管理者（スタッフ）編集ダイアログ --}}
<x-confirm-dialog id="modifyStaffConfirm" type="confirm">
    <x-slot name="title">編集</x-slot>
    <div class="row">
        <div class="col">
            <label for="modifyStaffLastName" class="form-label">姓<x-signs.require /></label>
            <input id="modifyStaffLastName" data-field="modifyStaffLastName" type="text" class="form-control" value="{!! old('last_name') !!}">
            @error('last_name')<p class="text-danger">{{ $message }}</p>@enderror
        </div>
        <div class="col">
            <label for="modifyStaffFirstName" class="form-label">名<x-signs.require /></label>
            <input id="modifyStaffFirstName" data-field="modifyStaffFirstName" type="text" class="form-control" value="{!! old('first_name') !!}">
            @error('first_name')<p class="text-danger">{{ $message }}</p>@enderror
        </div>
    </div>
    <div class="row">
        <label for="modifyStaffEmail" class="form-label">メールアドレス<x-signs.require /></label>
        <input id="modifyStaffEmail" data-field="modifyStaffEmail" type="email" class="form-control" value="{!! old('email') !!}">
        @error('email')<p class="text-danger">{{ $message }}</p>@enderror
    </div>
    <div class="form-check">
        <input id="modityStaffPasswordReset" data-field="modityStaffPasswordReset" class="form-check-input" type="checkbox" value="{!! Flags::ON !!}">
        <label class="form-check-label" for="modityStaffPasswordReset">パスワードをリセットする</label>
    </div>
    <br>
    <p class="text-start text-danger">※「パスワードをリセットする」をチェックすると、パスワードを自動生成し上記メールアドレスに通知します。</p>
    <x-slot name="ok_button">保存する</x-slot>
    <x-slot name="cancel_button">いいえ</x-slot>
</x-confirm-dialog>

{{-- 管理者（スタッフ）削除ダイアログ --}}
<x-confirm-dialog id="removeStaffConfirm" type="confirm">
    <x-slot name="title">新規登録</x-slot>
    <div class="row">
        <div class="col">
            <label for="removeStaffLastName" class="form-label">姓</label>
            <input id="removeStaffLastName" data-field="removeStaffLastName" type="text" class="form-control" readonly>
        </div>
        <div class="col">
            <label for="removeStaffFirstName" class="form-label">名</label>
            <input id="removeStaffFirstName" data-field="removeStaffFirstName" type="text" class="form-control" readonly>
        </div>
    </div>
    <div class="row">
        <label for="removeStaffEmail" class="form-label">メールアドレス</label>
        <input id="removeStaffEmail" data-field="removeStaffEmail" type="email" class="form-control" readonly>
    </div>
    <br>
    <p class="text-start text-danger">※削除した場合、戻すことはできません。<br>再登録が必要になります。</p>
    <x-slot name="ok_button">削除する</x-slot>
    <x-slot name="cancel_button">いいえ</x-slot>
</x-confirm-dialog>
