@use(App\ReserveTypes)
{{ $user->last_name }}様
下記のご予約がされていましたが、チェックインされませんでした。
当日にチェックインされない場合でも、回数券は消費いたしますが、回数券が不足しております。
必要枚数の回数券を購入してください。

@if($reserve->type == ReserveTypes::LUNCHBOX)
■ご予約日／受取時間
{{ $reserve->date->format('m月d日') }} {{ time_to_hhmm($reserve->time) }}

■ご予約個数
{{ $reserve->reserve_count }}個
@else
■ご予約日時
{{ $reserve->date->format('m月d日') }} {{ time_to_hhmm($reserve->time) }}～

■ご予約人数
{{ $reserve->reserve_count }}人
@endif

■回数券の必要枚数
{{ $required_count }}枚
