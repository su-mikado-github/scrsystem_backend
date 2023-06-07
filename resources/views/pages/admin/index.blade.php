@extends('layouts.admin')

@section('page.title')
<span>管理画面<span>
@endsection

@section('side-menu')
<x-virtical-menu id="idLeftSideMenu" category="left.side.menu" item-key="root" />
@endsection

@section('main')
コンテンツ
@endsection
