<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Models\LineWebhook;
use App\Models\LineUser;
use App\Models\User;

use App\LineApi;

class LineController extends Controller {
    /**
     * Webhook（フォローイベント）の処理
     */
    protected function webhook_follow(array $webhook) {
        $line_owner_id = $webhook['source']['userId'];

        $get_bot_profile = $this->line_api()->get_bot_profile($line_owner_id);
        if ($get_bot_profile === false) {
            logger()->error(sprintf('[get_bot_profile] %s', $line_owner_id));
            return false;
        }

        $display_name = $get_bot_profile->displayName;
        $profile_picture_url = $get_bot_profile->pictureUrl;

        $line_user = new LineUser();
        $line_user->line_owner_id = $line_owner_id;
        $line_user->token = strval(Str::uuid());
        $line_user->profile_picture_url = $profile_picture_url;
        $line_user->display_name = $display_name;
        $this->save($line_user);

        $user = new User();
        $user->line_user_id = $line_user->id;
        $this->save($user);

        $query = [
            'token' => $line_user->token,
        ];
        $url = sprintf('%s?%s', route('mypage'), http_build_query($query));
        $message = view('templates.line.follow')->with('url', $url)->render();
        if ($this->line_api()->push_messages($line_user->line_owner_id, [ $message ]) === false) {
            logger()->warning(sprintf('[get_bot_profile] %s', $line_owner_id));
        }

        return true;
    }

    protected function webhook_unfollow(array $webhook) {
        $line_owner_id = $webhook['source']['userId'];
        $line_user = LineUser::enabled()->lineOwnerIdBy($line_owner_id)->first();
        if (isset($line_user)) {
            if (isset($line_user->user)) {
                $this->remove($line_user->user);
            }
            $this->remove($line_user);
        }

        return true;
    }

    //
    public function webhook(Request $request) {
        //
        $data = file_get_contents('php://input');
        $webhook_json = json_decode($data, true);
//        logger()->debug($webhook_json);

        return DB::transaction(function() use($data, $webhook_json) {
            $line_webhook = new LineWebhook();
            $line_webhook->data = $data;
            $line_webhook->save();

            $events = $webhook_json['events'];
            foreach ($events as $event) {
                $type = $event['type'];
                if ($type === 'follow') {
                    if ($this->webhook_follow($event) === false) {
                        return response('', 400);
                    }
                }
                else if ($type === 'unfollow') {
                    if ($this->webhook_unfollow($event) === false) {
                        return response('', 400);
                    }
                }
            }

            return response('', 200);
        });

    }
}
