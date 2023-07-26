@extends('layouts.default')

@use(Illuminate\Support\Facades\Route)
@use(Illuminate\Support\Str)

@section('page.title')
開発用トップ
@endsection

@section('main')
<dl>
<dd><a href="/" class="text-dark">ホーム</a></dd>
<dt><hr></dt>
<dd><h5>■LINE公式チャンネル</h5></dd>
<dd><a href="{{ route('checkin') }}" class="text-dark">チェックイン</a></dd>
<dd><a href="{{ route('reserve') }}" class="text-dark">ご予約</a></dd>
<dd><a href="{{ route('reserve.change') }}" class="text-dark">時間変更</a></dd>
<dd><a href="{{ route('mypage') }}" class="text-dark">マイページ</a></dd>
<dd><a href="{{ route('dish_menu') }}" class="text-dark">メニュー</a></dd>
<dd><a href="{{ route('buy_ticket') }}" class="text-dark">回数券の購入</a></dd>
<dt><hr></dt>
<dd><h5>■食堂</h5></dd>
<dd><a href="{{ route('dining_hall') }}" class="text-dark">受付</a></dd>
<dt><hr></dt>
<dd><h5>■運営</h5></dd>
<dd><a href="{{ route('login') }}" class="text-dark">管理ツール</a></dd>
</dl>
{{-- <hr>
<dl>
@foreach(Route::getRoutes()->getRoutesByName() as $route)
@php
$matches = [];
$match_count = preg_match_all('/[{].+?[?]?[}]/i', $route->uri(), $matches);
@endphp
<dt>{{ $route->getName() }}({{ collect($route->parameterNames())->join(',') }}) => {{ Str::start($route->uri(), '/') }}</dt>
<dd>@foreach($matches as $match) {{ json_encode($match) }}@endforeach</dd>
@endforeach
</dl> --}}
@endsection
