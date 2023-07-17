{{ $user->last_name }}様
下記のご予約がされていましたが、チェックインされませんでした。
当日にチェックインされない場合でも、回数券は消費いたしますのでご承知ください。

■ご予約日／受取時間
{{ $reserve->date->format('m月d日') }} {{ time_to_hhmm($reserve->time) }}

■ご予約個数
{{ $reserve->reserve_count }}個

■回数券の消費枚数
{{ $reserve->reserve_count }}枚

