@isset($name)
<i class="{!! $name ?? 'fa-solid fa-question' !!} {!! (isset($class) ? $class : '') !!}" {{ $attributes->except([ 'class', 'name' ]) }}></i>
@else
<i class="fa-solid fa-question text-danger" {{ $attributes->except([ 'class', 'name' ]) }}></i>
@endif
