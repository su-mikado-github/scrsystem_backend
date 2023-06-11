@use(App\Flags)
@use(App\ReserveTypes)
@eval(list($hour, $minute, $second) = explode(':', $reserve->time))
{{ $user->last_name }}様
食堂のご予約（{{ $reserve->date->format('m月d日') }} {{ sprintf('%02d:%02d', $hour, $minute) }}～ {{ $reserve->reserve_count }}人）をキャンセルしました。

