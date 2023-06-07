<?php
namespace App\Console\Commands;

use Carbon\Carbon;

use Illuminate\Console\Command;

use App\Models\Calendar;
use App\Models\EmptyState;

class BuildEmptyState extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:empty_state {date? : (任意) YYYY-MM-DD形式。省略時は実行時の日付} {--months= : (任意) 生成する月数。省略時は1ヶ月}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '空き状況の構築（--monthsで指定した月数後の月末日まで構築）';

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
        $months = $this->option('months') ?? 1;

        $start_date = $date;
        $end_date = $date->copy()->addMonth($months)->addDays()->endOfMonth();

        logger()->info("空き状況の更新（開始）: {$start_date->format('Y-m-d')}～{$end_date->format('Y-m-d')}");

        EmptyState::periodBy($start_date, $end_date)->delete();

        $calendars = Calendar::periodBy($start_date, $end_date)->orderBy('date')->get();
        foreach ($calendars as $calendar) {
            logger()->info("{$calendar->date->format('Y-m-d')}");
            EmptyState::rebuild($calendar->date, '00:00:00', '23:59:00');
        }

        logger()->info("空き状況の更新（終了）: {$start_date->format('Y-m-d')}～{$end_date->format('Y-m-d')}");

        return 0;
    }
}
