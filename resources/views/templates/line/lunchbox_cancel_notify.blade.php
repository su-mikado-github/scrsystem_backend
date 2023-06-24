@use(App\Flags)
@use(App\ReserveTypes)
{{ $reserve->date->format('m月d日') }}にご予約されていたお弁当（{{ $reserve->reserve_count }}個）がキャンセルされました。
よろしければ、同日にお弁当を予約してください。

※↓こちらより、ご予約が可能です。
{!! route('reserve.lunchbox', [ $reserve->date->format('Y-m-d') ]) !!}

