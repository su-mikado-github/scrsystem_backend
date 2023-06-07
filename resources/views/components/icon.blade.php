@isset($name)
<i class="{!! $name ?? 'fa-solid fa-question' !!} mx-1 {!! (isset($class) ? $class : '') !!}" {{ $attributes->except([ 'class', 'name' ]) }}></i>
@else
<i class="fa-solid fa-question mx-1 text-danger" {{ $attributes->except([ 'class', 'name' ]) }}></i>
@endif
