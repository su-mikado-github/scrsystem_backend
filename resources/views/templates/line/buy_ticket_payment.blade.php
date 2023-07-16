@use(App\Flags)
{{ $user->last_name }}様
食券をご購入いただき、ありがとうございます。
お支払いの確認が取れました。

■食券の残数
{{ $user->last_ticket_count }}枚

引き続き、下記のURLよりチェックインを行ってください。
{{ $checkin_url }}
