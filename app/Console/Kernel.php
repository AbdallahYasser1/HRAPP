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
        $schedule->command('generate:slips')->monthlyOn(1, '00:00');
        $schedule->command('makerequestcanceled')->dailyAt('00:00');
        $schedule->command('generate:attendance')->dailyAt('00:00');
        $schedule->command('deduct:absence')->dailyAt('11:59');
        $schedule->command('absent:employee')->dailyAt('11:58');
        $schedule->command('StatusChange')->everyMinute();
        $schedule->command('finishrequest')->everyMinute();

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
