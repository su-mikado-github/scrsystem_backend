@use(App\Flags)
{{ $user->last_name }}様
本日は下記の予定で、お弁当の予約がされております。

■ご予約日
{{ $reserve->date->format('m月d日') }}

■ご予約個数
{{ $reserve->reserve_count }}個
