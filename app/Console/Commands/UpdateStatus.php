<?php

namespace App\Console\Commands;

use Carbon\Carbon;

use Illuminate\Console\Command;

use App\Models\Reserve;

class UpdateStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:status {date? : (任意) YYYY-MM-DD形式。省略時は実行時の日付}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '利用状況の更新（予約日が過ぎた予約をキャンセル扱いにする）';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $date = Carbon::parse($this->argument('date') ?? date('Y-m-d'));

        logger()->info("利用状況の更新（開始）: {$date->format('Y-m-d')}");

        // 予約日にチェックインされない予約をキャンセル扱いにする
        $reserves = Reserve::where('date', '<', $date)->enabled()->unCanceled()->noCheckin()->orderBy('user_id')->get();
        foreach ($reserves as $reserve) {
            $reserve->cancel_dt = now();
            $reserve->updated_id = 1;
            $reserve->updated_at = now();
            $reserve->save();

            logger()->warning(sprintf('[%d: %s %s] 予約日（%s）にチェックインされなかったため自動的にキャンセルになります。',
                $reserve->user_id, $reserve->user->last_name, $reserve->user->first_name, $reserve->date->format('Y-m-d')));
        }

        logger()->info("利用状況の更新（終了）: {$date->format('Y-m-d')}");

        return 0;
    }
}
