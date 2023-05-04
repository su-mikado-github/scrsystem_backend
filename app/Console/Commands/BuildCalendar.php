<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

use Illuminate\Console\Command;

use App\Models\Calendar;

use App\Weekdays;

class BuildCalendar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:calendar {date? : (任意) YYYY-MM-DD形式。省略時は実行時の日付} {--years= : (任意) 生成する年数。省略時は1年}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'カレンダーの構築';

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
        $years = $this->option('years') ?? 1;

        $url = 'https://holidays-jp.github.io/api/v1/date.json';
        $response = file_get_contents($url);
        $holidays = json_decode($response, true);
        $period = CarbonPeriod::create($date, $date->copy()->addYear($years));
        foreach ($period as $date) {
            $key = $date->format('Y-m-d');
            $weekday = Weekdays::fromDate($date);
            $calendar = Calendar::where('date', $key)->first() ?? new Calendar();
            if (!$calendar->id) {
                $calendar->date = $date;
                $calendar->year = $date->year;
                $calendar->month = $date->month;
                $calendar->day = $date->day;
                $calendar->weekday = $weekday->id;
                $calendar->week_of_month = $date->weekOfMonth;
                $calendar->week_of_year = $date->weekOfYear;
                $calendar->created_id = 0;
                $calendar->updated_id = 0;
            }
            else {
                $calendar->data_version ++;
                $calendar->updated_id = 0;
            }
            $calendar->is_holiday = isset($holidays[$key]);
            $calendar->holiday_name = $holidays[$key] ?? null;
            $calendar->save();
        }

        return 0;
    }
}
