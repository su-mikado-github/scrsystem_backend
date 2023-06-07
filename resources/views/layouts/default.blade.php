<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link href="/css/app.css" rel="stylesheet">
<link href="/default.css" rel="stylesheet">
@stack('links')
@stack('styles')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="/js/app.js" type="text/javascript"></script>
<script src="/default.js" type="text/javascript"></script>
@include('includes.enums')
@stack('scripts')
<title>@yield('title', ($title ?? '[CLUBHOUSE提供] 食堂予約システム（仮）'))</title>
</head>
<body class="scrs-bg">
<header class="scrs-bg position-sticky w-100 p-3" style="top:0;left:0;">
<h3 class="scrs-bg-main text-center p-1">@yield('page.title', '（不明）')</h3>
@yield('header')
</header>
<main class="p-3 mb-3"><form method="POST">@csrf
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
@if(session('warning'))
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    {{ session('warning') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
@yield('main')
</form>
</main>
<footer class="scrs-bg-footer text-center position-sticky w-100 py-1" style="bottom:0;left:0;">
@yield('footer')
<span class="font-weight-bold">CLUBHOUSE &copy; 2023</span>
</footer>
@stack('dialogs')
<iframe src="{!! url('/ping') !!}" class="d-none" style="width:0;height:0;"></iframe>
</body>
</html>
