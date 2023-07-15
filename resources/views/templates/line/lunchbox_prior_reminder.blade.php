@use(App\Flags)
{{ $user->last_name }}様
{{ ($prior_mins >= 60 ? sprintf('%d時間%d分', floor($prior_mins, 60), ($prior_mins % 60)) : sprintf('%d分', $prior_mins)) }}後に、本日の予約時間となります。

■ご予約内容
お弁当

■ご予約日／受取時間
{{ $reserve->date->format('m月d日') }} {{ time_to_hhmm($reserve->time) }}

■ご予約個数
{{ $reserve->reserve_count }}個
