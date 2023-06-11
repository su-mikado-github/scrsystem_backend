@use(App\Flags)
@use(App\ReserveTypes)
{{ $user->last_name }}様
ご予約されていた{{ $reserve->date->format('m月d日') }}に、チェックインが完了しました。
