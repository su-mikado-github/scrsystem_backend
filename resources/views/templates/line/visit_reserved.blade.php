@use(App\Flags)
{{ $user->last_name }}様
下記の内容で、{{ $reserve->date->format('m月d日') }}の食堂利用のご予約を承りました。

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
