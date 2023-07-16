@use(App\Flags)
{{ $user->last_name }}様
食券をご購入いただき、ありがとうございます。
食堂にてお支払いください。

■購入日時
{{ $buy_ticket->buy_dt->format('Y年m月d日 H:i') }}

■購入した食券
{{ $buy_ticket->ticket_count }}枚
