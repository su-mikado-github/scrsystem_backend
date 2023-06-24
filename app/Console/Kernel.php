<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('build:calendar')->monthly();
        $schedule->command('build:empty_state')->monthly();
        $schedule->command('update:status')->daily();
        $schedule->command('execute:reminder --all')->dailyAt('9:00');
        $schedule->command('execute:reminder')->everyMinute();
        $schedule->call(function() { logger()->debug(sprintf('schedule run: %s', now()->format('Y-m-d H:i:s'))); })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
