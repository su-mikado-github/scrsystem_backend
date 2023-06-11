@use(App\Flags)
@use(App\ReserveTypes)
@eval(list($hour, $minute, $second) = explode(':', $reserve->time))
{{ $user->last_name }}様
食堂のご予約（{{ $reserve->date->format('m月d日') }} {{ sprintf('%02d:%02d', $hour, $minute) }}～ {{ $reserve->reserve_count }}人）がキャンセルされました。

※是非、食堂のご利用にご協力ください。
※↓こちらより、ご予約が可能です。
{!! route('reserve.visit', [ $reserve->date->format('Y-m-d') ]) !!}

