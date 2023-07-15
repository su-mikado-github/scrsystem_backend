@use(App\Flags)
{{ $user->last_name }}様
本日は下記の予定で、お弁当の予約がされております。

■ご予約日／受取時間
{{ $reserve->date->format('m月d日') }} {{ time_to_hhmm($reserve->time) }}

■ご予約個数
{{ $reserve->reserve_count }}個
