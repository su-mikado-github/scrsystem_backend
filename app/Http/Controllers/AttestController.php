<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\LineUser;

class AttestController extends Controller {
    //
    public function login(Request $request, $token=null) {
        if (isset($token)) {
            $line_user = LineUser::where('token', $token)->where('is_delete', false)->first();
            if (isset($line_user)) {
                $user = $line_user->user;
                if (empty($user)) {
                    $user = new User();
                }

                return $this->ok([
                    'token' => $line_user->token,
                    'profile_picture_url' => $line_user->profile_picture_url,
                    'line_name' => $line_user->line_name,
                ]);
            }
        }

        $screen_path = $request->input('screen_path');
        abort_if(!$line_user->screen_path, 404, '画面が存在しません。');

        $line_user = new LineUser();
        $line_user->token = Str::random(40);
        $line_user->screen_path = $screen_path;
        $line_user->save();

        $line_url = config('line.oauth.url');
        $response_type = 'code';
        $client_id = config('line.oauth.client_id');
        $redirect_uri = urlencode(secure_url(config('line.oauth.redirect_uri')));
        $state = $line_user->token;
        $scope = 'profile%20openid';

        $query = collect(compact('response_type', 'client_id', 'redirect_uri', 'state', 'scope'))->map(function($v, $k) {
            return sprintf('%s=%s', $k, $v);
        });
        return $this->ok([
            'url' => sprintf('%s?%s', $line_url, $query->join("&")),
        ]);
    }
}
