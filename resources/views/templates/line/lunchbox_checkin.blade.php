@use(App\Flags)
@use(App\ReserveTypes)
{{ $user->last_name }}様
{{ $reserve->date->format('m月d日') }} {{ time_to_hhmm($reserve->time) ?? '' }}にご予約されていたお弁当（{{ $reserve->reserve_count }}個）の受け取りが完了しました。
