{{ $user->last_name }} {{ $user->first_name }} 様

パスワードがリセットされました。
下記のURLにて、パスワードを再設定してください。

■URL
{{ route('new_password', [ 'reset_token'=>$user->reset_token ]) }}
