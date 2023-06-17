{{ $user->last_name }} {{ $user->first_name }} 様

CLUBHOUSEの管理者として登録されました。
下記のURLにて、ログインしてください。

■URL
{{ route('login') }}

■ログイン情報
  メールアドレス: {{ $user->email }}
  パスワード: {{ $password }}
