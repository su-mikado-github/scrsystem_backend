<?php

namespace App\Console\Commands;

use Carbon\Carbon;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

use App\Flags;

use App\LineApi;

use App\Models\Reserve;

class UpdateStatus extends Command
{
    private $line_api = null;

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
    public function __construct(LineApi $line_api) {
        parent::__construct();
        //
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

        logger()->info("利用状況の更新（開始）: {$date->format('Y-m-d')}");

        $count = 0;

        // 予約日にチェックインされない予約をキャンセル扱いにする
        $reserves = Reserve::with([ 'user' ])->where('date', '<', $date)->enabled()->unCanceled()->noCheckin()->where('is_through', Flags::OFF)->orderBy('user_id')->get();
        foreach ($reserves as $reserve) {
            logger()->warning(sprintf('[%d: %s %s] 予約日（%s）にチェックインされませんでしたが、食券は消費します。',
                $reserve->user_id, $reserve->user->last_name, $reserve->user->first_name, $reserve->date->format('Y-m-d')));

            // 購入回数券を引き当てる
            $valid_tickets = $reserve->user->valid_tickets()->validateBy()->orderBy('buy_dt')->get();
            $buy_ticket_ids = collect();
            $valid_ticket = null;
            $valid_ticket_count = 0;
            for ($i=0; $i<$reserve->reserve_count; $i++) {
                if ($valid_ticket_count == 0) {
                    if ($valid_tickets->count() == 0) {
                        break;
                    }

                    $valid_ticket = $valid_tickets->shift();
                    if (empty($valid_ticket)) {
                        break;
                    }
                    $valid_ticket_count = op($valid_ticket)->valid_ticket_count ?? 0;
                }
                $buy_ticket_ids->push($valid_ticket->buy_ticket_id);
                $valid_ticket_count --;
            }

            $result = DB::transaction(function() use($reserve, $buy_ticket_ids) {
                $reserve->is_through = Flags::ON;
                $reserve->updated_at = now();
                $reserve->updated_id = 0;
                $reserve->data_version ++;
                $reserve->save();

                // logger()->debug(sprintf('%s(%s) => %s', __FILE__, __LINE__, print_r([ 'buy_ticket_ids.count'=>$buy_ticket_ids->count(), 'person_count'=>$person_count ], true)));
                if ($buy_ticket_ids->count() < $reserve->reserve_count) {
                    $required_count = $reserve->reserve_count - $buy_ticket_ids->count();

                    $message = view('templates.line.update_status_shortage_ticket')->with('user', $reserve->user)->with('reserve', $reserve)->with('required_count', $required_count)->render();
                    if (!$this->line_api->push_messages($reserve->user->line_user->line_owner_id, [ $message ])) {
                        DB::rollBack();
                        logger()->error(sprintf('[update_status: %d %s] %s %s LINEメッセージの送信に失敗しました', $reserve->user_id, $reserve->user->last_name, $reserve->user->first_name, $reserve->date->format('Y-m-d'), time_to_hhmm($reserve->time)));
                        return false;
                    }
                }
                else {
                    $buy_ticket_id_list = $buy_ticket_ids->toArray();
                    foreach ($reserve->use_tickets as $use_ticket) {
                        $valid_ticket = $reserve->user->valid_tickets()->validateBy()->first();
                        abort_if(!$valid_ticket, 400, __('messages.warning.ticket_by_short'));
                        $use_ticket->buy_ticket_id = $valid_ticket->buy_ticket_id;
                        $use_ticket->updated_at = now();
                        $use_ticket->updated_id = 0;
                        $use_ticket->data_version ++;
                        $use_ticket->save();
                    }

                    $message = view('templates.line.update_status')->with('user', $reserve->user)->with('reserve', $reserve)->render();
                    if (!$this->line_api->push_messages($reserve->user->line_user->line_owner_id, [ $message ])) {
                        DB::rollBack();
                        logger()->error(sprintf('[update_status: %d %s] %s %s LINEメッセージの送信に失敗しました', $reserve->user_id, $reserve->user->last_name, $reserve->user->first_name, $reserve->date->format('Y-m-d'), time_to_hhmm($reserve->time)));
                        return false;
                    }
                }

                return true;
            });
            if ($result) {
                $count ++;
            }
        }

        logger()->info("利用状況の更新（終了）: {$date->format('Y-m-d')} 処理件数：{$count}件");

        return 0;
    }
}
