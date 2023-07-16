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
        logger()->debug([
            'request'=>$request,
            self::SCRS_TOKEN_NAME => $request->cookie(self::SCRS_TOKEN_NAME),
            'query.token' => $request->query('token'),
        ]);

        $scrs_token = $request->query('token', $request->cookie(self::SCRS_TOKEN_NAME));
        if (!$scrs_token) {
            return redirect(route('error'))
                ->cookie(self::SCRS_TOKEN_NAME, $scrs_token, self::SCRS_TOKEN_MINUTES)
                ->with('error', '最初にLINEで通知されたマイページへのリンクから、ご利用者様の情報をご登録ください。');
        }

        $screen_path = $request->path();
        $line_user = LineUser::enabled()->tokenBy($scrs_token)->first();
        abort_if(!$line_user, 400, __('messages.error.follow_retry'));

        Auth::login($line_user->user);
        if ($line_user->user->is_initial_setting || $screen_path == 'mypage') {
            Cookie::queue(self::SCRS_TOKEN_NAME, $scrs_token, self::SCRS_TOKEN_MINUTES);
            return $next($request);
        }
        else {
            return redirect(route('mypage'))
                ->cookie(self::SCRS_TOKEN_NAME, $scrs_token, self::SCRS_TOKEN_MINUTES)
                ->with('warning', 'ご利用者の情報を登録してください。');
        }
    }
}
