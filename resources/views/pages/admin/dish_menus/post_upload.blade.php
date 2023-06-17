@extends('layouts.admin')

@use(Carbon\Carbon)
@use(App\DishTypes)

<x-script>
class AdminDishMenuPostUploadPage extends SCRSPage {
    constructor() {
        super();
        //
    }
}

SCRSPage.startup(()=>new AdminDishMenuPostUploadPage());
</x-script>

@section('page.title')
<span>管理画面<span>≫<span>メニュー編集<span>
@endsection

@section('side-menu')
<x-virtical-menu id="idLeftSideMenu" category="left.side.menu" item_key="dish_menu" />
@endsection

@section('main')
<h5>アップロード・エラー</h5>
@foreach($errors as $error)
<div>
<h6>{!! $error->line_no !!}行目</h6>
<ul>
@foreach($error->causes as $cause)
<li>{{ $cause }}</li>
@endforeach
</ul>
</div>
@endforeach
@endsection
