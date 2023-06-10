@use(App\Flags)
{{ $user->last_name }}様
下記の内容で、お弁当のご予約を承りました。

■ご予約日
{{ $reserve->date->format('m月d日') }}

■ご予約個数
{{ $reserve->reserve_count }}個
