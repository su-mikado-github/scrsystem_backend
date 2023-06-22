@use(Carbon\Carbon)
@use(App\ReserveTypes)
@use(App\Weekdays)
@extends('layouts.default')

<x-script>
import { SCRSPage } from "/scrs-pages.js";
import { SCRSConfirmDialog } from "/dialogs/confirm-dialog.js";

class ChangePage extends SCRSPage {
    constructor() {
        super();
        //
    }
}

SCRSPage.startup(()=>new ChangePage());
</x-script>

@section('page.title')
ご予約日の変更／キャンセル
@endsection

@section('main')
<h2>
    <ruby>
        <rb>{{ $user->last_name }}</rb>
        <rt>{{ $user->last_name_kana }}</rt>
    </ruby>
    <ruby>
      <rb>{{ $user->first_name }}</rb>
      <rt>{{ $user->first_name_kana }}</rt>
    </ruby>
    様
</h2>

<div class="px-5 py-4 scrs-sheet-normal">
    <h3 class="text-center mb-4">ご予約内容</h3>
    <p>※ご予約はされていません。</p>
</div>
@endsection
