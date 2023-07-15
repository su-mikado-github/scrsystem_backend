@use(App\MenuItemTypes)
<x-style>
[data-type='virtical-menu'].list-group {
    background-color: transparent!important;
}

[data-type='virtical-menu'].list-group > .list-group-item {
    background-color: transparent!important;
    border: 0px none transparent;
    padding-top: 0.1em;
    padding-bottom: 0.1em;
}

[data-type='virtical-menu'].list-group > .list-group-item.active {
    position: relative;
    left: -16px;
    color: black;
}

[data-type='virtical-menu'].list-group > .list-group-item.active::before {
    display: inline-block;
    top: 1px;
    left: -2px;
    width: 14px;
    position: relative;
    content: url(/images/icons/triangle_black.svg);
}
</x-style>
<x-script>
</x-script>
<ul id="{!! $id !!}" data-type="virtical-menu" class="list-group">
@foreach(($menus ?? collect()) as $menu)
@debug($menu)
@if($menu->item_type == MenuItemTypes::SEPARATER)
@debug(sprintf('%s(%d)', __FILE__, __LINE__))
<li class="list-group-item">&nbsp;</li>
@elseif($menu->item_type == MenuItemTypes::INSIDE_LINK)
@debug(sprintf('%s(%d)', __FILE__, __LINE__))
<a href="{!! url($menu->path) !!}" class="list-group-item list-group-item-action {!! ($menu->item_key == $item_key ? 'active' : '') !!}"><u>{{ $menu->name }}</u></a>
@elseif($menu->item_type == MenuItemTypes::OUTSIDE_LINK)
@debug(sprintf('%s(%d)', __FILE__, __LINE__))
<a href="{!! $menu->url ?? '#' !!}" target="_blank" class="list-group-item list-group-item-action"><u>{{ $menu->name }}</u></a>
@elseif($menu->item_type == MenuItemTypes::ACTION)
@debug(sprintf('%s(%d)', __FILE__, __LINE__))
<a href="#" class="list-group-item list-group-item-action" onclick="{!! $menu->action ?? '' !!}"><u>{{ $menu->name }}</u></a>
@elseif($menu->item_type == MenuItemTypes::SUB_MENUS)
@debug(sprintf('%s(%d)', __FILE__, __LINE__))
{{-- 現在未対応 --}}
@endif
@endforeach
</ul>
