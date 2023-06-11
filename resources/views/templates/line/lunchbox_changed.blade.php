@use(App\Flags)
{{ $user->last_name }}様
お弁当のご予約を下記のように変更しました。

■ご予約日
{{ $reserve->date->format('m月d日') }}

■ご予約個数
{{ $reserve->reserve_count }}個
