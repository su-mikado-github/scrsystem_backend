@use(App\MenuItemTypes)
<x-style>
[data-type='horizontal-tab'].nav-tabs {
    background-color: transparent!important;
}

[data-type='horizontal-tab'].nav-tabs > .nav-item {
    background-color: #eeeeee!important;
    color: black;
}

[data-type='horizontal-tab'].nav-tabs > .nav-item.active {
    background-color: white!important;
}

[data-type='horizontal-tab'].nav-tabs > .nav-item > .nav-link {
    color: black;
}

</x-style>
<x-script>
</x-script>
<ul id="{!! $id !!}" data-type="horizontal-tab" class="nav nav-tabs">
@foreach(($menus ?? collect()) as $menu)
@if($menu->item_type == MenuItemTypes::SEPARATER)
{{-- 現在未対応 --}}
@elseif($menu->item_type == MenuItemTypes::INSIDE_LINK)
<li class="nav-item">
    <a href="{!! url($menu->path) !!}" class="nav-link {{ ($menu->item_key == $item_key ? 'active fw-bold' : '') }}"><u>{{ $menu->name }}</u></a>
</li>
@elseif($menu->item_type == MenuItemTypes::OUTSIDE_LINK)
<li class="nav-item">
    <a href="{!! url($menu->url) ?? '#' !!}" target="_blank" class="nav-link"><u>{{ $menu->name }}</u></a>
</li>
@elseif($menu->item_type == MenuItemTypes::ACTION)
<li class="nav-item">
    <a href="#" onclick="{!! $menu->action ?? '' !!}" class="nav-link"><u>{{ $menu->name }}</u></a>
</li>
@elseif($menu->item_type == MenuItemTypes::SUB_MENUS)
@endif
@endforeach
</ul>
