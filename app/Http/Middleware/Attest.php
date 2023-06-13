<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

use App\Flags;

use App\Models\User;
use App\Models\LineUser;

class Attest
{
    const SCRS_TOKEN_NAME = 'SCRS-Token';
    const SCRS_TOKEN_MINUTES = 525600;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $scrs_token = $request->cookie(self::SCRS_TOKEN_NAME, $request->query('token'));
        abort_if(!$scrs_token, 400, __('messages.error.not_line_follow'));

        $screen_path = $request->path();
        $line_user = LineUser::enabled()->tokenBy($scrs_token)->first();
        abort_if(!$line_user, 400, __('messages.error.follow_retry'));

        Auth::login($line_user->user);
        if ($user->is_initial_setting || $screen_path == 'mypage') {
            Cookie::queue(self::SCRS_TOKEN_NAME, $scrs_token, self::SCRS_TOKEN_MINUTES);
            return $next($request);
        }
        else ($screen_path != 'mypage') {
            return redirect(route('mypage'))
                ->cookie(self::SCRS_TOKEN_NAME, $scrs_token, self::SCRS_TOKEN_MINUTES)
                ->with('warning', 'ご利用者の情報を登録してください。');
        }

        // if (isset($line_user)) {
        //     $line_user->screen_path = $screen_path;
        //     $line_user->save();
        //     if (!empty($line_user->line_owner_id)) {
        //         $user = $line_user->user()->where('is_delete', Flags::OFF)->first();
        //         if (empty($user)) {
        //             $user = new User();
        //             $user->line_user_id = $line_user->id;
        //             $user->is_admin = false;
        //             $user->last_login_dt = now();
        //             $user->checkin_token = Str::uuid();
        //             $user->save();
        //         }
        //         logger()->debug(sprintf('[%s] SCRS-Token: %s', $user->id, $scrs_token));

        //         Auth::login($user);
        //         if ($user->is_initial_setting || $screen_path == 'mypage') {
        //             Cookie::queue(self::SCRS_TOKEN_NAME, $scrs_token, self::SCRS_TOKEN_MINUTES);
        //             return $next($request);
        //         }
        //         else if ($screen_path != 'mypage') {
        //             return redirect(route('mypage'))
        //                 ->cookie(self::SCRS_TOKEN_NAME, $scrs_token, self::SCRS_TOKEN_MINUTES)
        //                 ->with('warning', 'ご利用者の情報を登録してください。');
        //         }
        //     }
        // }
        // else {
        //     $line_user = new LineUser();
        //     $line_user->token = Str::random(40);
        //     $line_user->screen_path = $screen_path;
        //     $line_user->save();

        //     $user = new User();
        //     $user->line_user_id = $line_user->id;
        //     $user->is_admin = false;
        //     $user->last_login_dt = now();
        //     $user->checkin_token = Str::uuid();
        //     $user->save();

        //     logger()->debug(sprintf('[%s] SCRS-Token: %s', $user->id, $scrs_token));
        // }

        // $line_url = config('line.oauth.url');
        // $response_type = 'code';
        // $client_id = config('line.oauth.client_id');
        // $redirect_uri = urlencode(secure_url(config('line.oauth.redirect_uri')));
        // $state = $line_user->token;
        // $scope = 'profile%20openid';

        // $query = collect(compact('response_type', 'client_id', 'redirect_uri', 'state', 'scope'))->map(function($v, $k) {
        //     return sprintf('%s=%s', $k, $v);
        // });
        // return redirect(sprintf('%s?%s', $line_url, $query->join("&")))
        //     ->cookie(self::SCRS_TOKEN_NAME, $line_user->token, self::SCRS_TOKEN_MINUTES);
    }
}
