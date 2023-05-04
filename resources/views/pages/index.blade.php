@extends('layouts.default')

@section('page.title')
開発用トップ
@endsection

@section('main')
<dl>
<dd><a href="/" class="text-dark">ホーム</a></dd>
<dt><hr></dt>
<dd><a href="/checkin" class="text-dark">チェックイン</a></dd>
<dd><a href="/reserve" class="text-dark">ご予約</a></dd>
<dd><a href="/reserve/change" class="text-dark">時間変更</a></dd>
<dd><a href="/mypage" class="text-dark">マイページ</a></dd>
<dd><a href="/dish_menu" class="text-dark">メニュー</a></dd>
<dd><a href="/buy_ticket" class="text-dark">回数券の購入</a></dd>
</dl>

@endsection