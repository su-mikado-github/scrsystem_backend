<?php

namespace App\Console\Commands;

use Carbon\Carbon;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\LineApi;

use App\ReserveTypes;

use App\Models\Reserve;

class ExecuteReminder extends Command
{
    private $line_api = null;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'execute:reminder {date? : (任意) YYYY-MM-DD形式。省略時は実行時の日付} {time? : (任意) HH:MM形式。省略時は実行時の時刻} {--all : (任意) 現在時点でまだチェックイン／受け取りしていない予約を対象とする} {--prior= : (任意)予約時間のどのくらい前の時刻に通知するかの分数}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '指定日のすべての予約（チェックイン予定／受け取り予定）または予約時間が指定時間後に迫っている場合にリマインドする。';

    protected function remined_all($date) {
        $reserves = Reserve::enabled()->dateBy($date)->noCheckin()->unCanceled()->whereNull('remind_dt')->orderBy('type')->orderBy('time')->get();

        return DB::transaction(function() use($reserves) {
            foreach ($reserves as $reserve) {
                $line_owner_id = $reserve->user->line_user->line_owner_id;
                $view_name = ($reserve->type == ReserveTypes::LUNCHBOX ? 'lunchbox_reminder' : 'visit_reminder');
                $message = view("templates.line.{$view_name}")->with('user', $reserve->user)->with('reserve', $reserve)->render();
                logger()->debug($message);
                if (!$this->line_api->push_messages($line_owner_id, [ $message ])) {
                    logger()->error(sprintf('[利用者ID:%d] 予約ID:%d', $reserve->user_id, $reserve->id));
                }
            }

            return $reserves->count();
        });
    }

    protected function remined_prior($date, $time, $prior_mins) {
        $target_mins = hhmm_to_mins($time) + $prior_mins;
        $target_time = mins_to_time($target_mins);

        $reserves = Reserve::enabled()->dateBy($date)->noCheckin()->unCanceled()->where(function($query) use($target_time) {
            $query->orWhere('time', '<=', $target_time);
        })->whereNull('remind_dt')->orderBy('type')->orderBy('time')->get();

        return DB::transaction(function() use($reserves, $prior_mins) {
            foreach ($reserves as $reserve) {
                $line_owner_id = $reserve->user->line_user->line_owner_id;
                $view_name = ($reserve->type == ReserveTypes::LUNCHBOX ? 'lunchbox_prior_reminder' : 'visit_prior_reminder');
                $message = view("templates.line.{$view_name}")->with('user', $reserve->user)->with('reserve', $reserve)->with('prior_mins', $prior_mins)->render();
                logger()->debug($message);
                if (!$this->line_api->push_messages($line_owner_id, [ $message ])) {
                    logger()->error(sprintf('[利用者ID:%d] 予約ID:%d', $reserve->user_id, $reserve->id));
                }

                //リマインド時間を設定
                $reserve->remind_dt = now();
                $reserve->updated_id = 0;
                $reserve->updated_at = now();
                $reserve->data_version ++;
                $reserve->save();
            }

            return $reserves->count();
        });
    }

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(LineApi $line_api)
    {
        parent::__construct();

        $this->line_api = $line_api;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $date = Carbon::parse($this->argument('date') ?? date('Y-m-d'));
        $time = $this->argument('time') ?? now()->format('H:i');
        $is_all = !empty($this->option('all'));
        $prior = intval($this->option('prior') ?? '30');
        logger()->info(sprintf('リマインド（開始） [条件] %s %s %s %d', $date->format('Y-m-d'), $time, ($is_all ? '当日すべての予約' : '指定時間'), $prior));

        //パラメータのバリデーション
        $rules = [
            'date' => [ 'nullable', 'date' ],
            'time' => [ 'nullable', 'regex:/^[0-9]{2}[:][0-9]{2}$/i' ],
        ];
        $messages = [
            'date.date' => 'YYYY-MM-DD形式で正しい日付を指定してください。',
            'time.regex' => 'HH:MM形式で正しい時刻を指定してください。',
        ];
        $validator = Validator::make($this->arguments(), $rules, $messages);
        if ($validator->fails()) {
            foreach ($errors->all() as $message) {
                $this->error($message);
            }
            return -1;
        }

        try {
            $count = 0;
            if ($is_all) {
                $count = $this->remined_all($date);
            }
            else {
                $count = $this->remined_prior($date, $time, $prior);
            }
            logger()->info($count==0 ? 'リマインド対象はありません。' : sprintf('%d件のリマインドをしました。', $count));
        }
        catch (\Exception $ex) {
            logger()->info('リマインド（エラー）');
            logger()->error($ex);
            return -1;
        }

        logger()->info('リマインド（終了）');
        return 0;
    }
}
