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
    protected function webhook_follow(LineApi $line_api, array $webhook) {
        $line_user = new LineUser();
        $line_user->line_owner_id = $webhook['source']['userId'];
        $line_user->token = Str::uuid();
        $line_user->save();

        $user = new User();
        $user->line_user_id = $line_user->id;
        $user->save();

        $query = [
            'token' => $line_user->token,
        ];
        $url = url('/mypage');
        $message = <<<END_OF_TEXT
CLUBHOUSE membershipへのご登録ありがとうございます。
下記のリンクより、マイページにてプロフィールのご記入をお願いします。

■マイページ
{$url}

※プロフィールの更新が終わりましたら、画面右上の「×」をタップして画面を閉じてください。
※サービスのご利用については、当チャネルの下部にあるメニューよりご利用できます。
END_OF_TEXT;
        $line_api->push_messages($line_user->line_owner_id, [ $message ]);
    }

    //
    public function webhook(Request $request, LineApi $line_api) {
        //
        $data = file_get_contents('php://input');
        $webhook_json = json_decode($data, true);
//        logger()->debug($webhook_json);

        DB::transaction(function() use($line_api, $data, $webhook_json) {
            $line_webhook = new LineWebhook();
            $line_webhook->data = $data;
            $line_webhook->save();

            collect($webhook_json['events'])
                ->each(function($item, $key) use($line_api) {
                    if ($item['type'] === 'follow') {
                        $this->webhook_follow($line_api, $item);
                    }
                })
            ;
        });

        return response('', 200);
    }
}
