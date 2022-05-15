<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Holiday;
use App\Models\Attendance;
use App\Models\Absence;

class AbsentEmployee extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'absent:employee';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Everyday at 11:59, check if there is any employee who is absent';

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
        $isTodayHoliday = Holiday::where('date', '=', date('Y-m-d'))->get()->first();
        
        if (!$isTodayHoliday) {
            $attendances = Attendance::where('date', date('Y-m-d'))->get();
            foreach ($attendances as $attendance) {
                if ($attendance->start_time == null) {
                    $user_id = $attendance->user_id;
                    $date = $attendance->date;
                    $absence = new Absence();
                    $absence->user_id = $user_id;
                    $absence->date = $date;
                    $absence->save();
                }
            }
        }
        return 0;
    }
}
