<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Holiday;
use App\Models\Attendance;

class GenerateAttendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:attendance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Everyday at 00:00, generate attendance for all employees';

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
            $users = User::all();
            foreach ($users as $user) {
                $isUserOnVacation = false;
                if (!$isUserOnVacation) {
                    $attendance = Attendance::where('user_id', $user->id)->where('date', date('Y-m-d'))->first();
                    if (!$attendance) {
                        $attendance = new Attendance();
                        $attendance->user_id = $user->id;
                        error_log($user->id);
                        $attendance->date = date('Y-m-d');
                        $attendance->save();
                    }
                }
            }
        }
        return 0;
    }
}
