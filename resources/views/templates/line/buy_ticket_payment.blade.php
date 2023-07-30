@use(App\Flags)
{{ $user->last_name }}様
食券をご購入いただき、ありがとうございます。
お支払いの確認が取れました。

■食券の残数
{{ $user->last_ticket_count }}枚

チェックインされる方は下記のURLをご利用ください。
{{ $checkin_url }}
