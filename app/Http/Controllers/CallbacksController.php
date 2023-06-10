<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

use App\LineApi;

use App\Models\LineUser;

class CallbacksController extends Controller {
    const SCRS_TOKEN_NAME = 'SCRS-Token';
    const SCRS_TOKEN_MINUTES = 525600;

    //
    public function line_login(Request $request) {
        $code = $request->input('code');
        $state = $request->input('state');
        abort_if(!$code || !$state, 404, '不正なアクセスです。');

        $line_user = LineUser::where('token', $state)->where('is_delete', false)->first();
        abort_if(!$line_user, 404, 'トークンが有効ではありません。');

        $user = $line_user->user()->where('is_delete', false)->first();
        abort_if(!$user, 404, '認証情報が有効ではありません。');

        //LINE: アクセストークンの取得
        $get_access_token = $this->line_api()->get_access_token($code, $state);
        abort_if(!$get_access_token, 400, 'LINEから情報が取得できません。');

        //LINE: プロフィールの取得
        $get_profile = $this->line_api()->get_profile($get_access_token->access_token);

        //        $line_user->id_token = $get_access_token->id_token;
        $line_user->line_owner_id = $get_profile->userId;
        $line_user->access_token = $get_access_token->access_token;
        $line_user->refresh_token = $get_access_token->refresh_token;
        $line_user->display_name = $get_profile->displayName;
        $line_user->profile_picture_url = $get_profile->pictureUrl;
        $line_user->save();

        Auth::login($user);
        if ($user->is_initial_setting) {
            return redirect(url("/{$line_user->screen_path}"))
                ->cookie(self::SCRS_TOKEN_NAME, $state, self::SCRS_TOKEN_MINUTES);
        }
        else {
            return redirect(route('mypage'))
                ->cookie(self::SCRS_TOKEN_NAME, $state, self::SCRS_TOKEN_MINUTES)
                ->with('warning', 'ご利用者の情報を登録してください。');
        }
    }
}
