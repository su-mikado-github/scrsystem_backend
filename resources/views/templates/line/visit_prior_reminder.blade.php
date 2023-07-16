@use(App\Flags)
{{ $user->last_name }}様
{{ ($prior_mins >= 60 ? sprintf('%d時間%d分', floor($prior_mins, 60), ($prior_mins % 60)) : sprintf('%d分', $prior_mins)) }}後に、本日の予約時間となります。

■ご予約内容
食堂のご利用

■ご予約日時
{{ $reserve->date->format('m月d日') }} {{ time_to_hhmm($reserve->time) }}～
@if((optional($user->affiliation_detail)->is_soccer ?? Flags::OFF) == Flags::OFF)

■ご予約人数
{{ $reserve->reserve_count }}人

■座席
@foreach($reserve->seat_groups as $seat_group)
{{ $seat_group->seat_count }}人席
@endforeach
@endif
