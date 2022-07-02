<?php

namespace App\Console\Commands;

use App\Models\Requestdb;
use App\Traits\AttendanceChecks;
use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Holiday;
use App\Models\Attendance;

class GenerateAttendance extends Command
{
    use AttendanceChecks;
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
    protected $description = 'Everyday at 00:00, generate attendance records for all employees';

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
     */
    public function handle()
    {
        $isTodayHoliday = Holiday::where('date', '=', date('Y-m-d'))->get()->first();
        $isWeekend = $this->checkWeekend();

        if (!$isTodayHoliday && !$isWeekend) {
            $users = User::all();
            foreach ($users as $user) {
                $vacatation = Requestdb::all()
                    ->where('user_id', $user->id)
                    ->where('start_date', date('Y-m-d'))
                    ->where('requestable_type', '=', "App\\Models\\Vacation")
                    ->whereIn('status', ['in-progress', 'approved']);

                $isUserOnVacation =!$vacatation->isEmpty(); // vacation -> true
                if (!$isUserOnVacation) {
                    $attendance = Attendance::where('user_id', $user->id)->where('date', date('Y-m-d'))->first();
                    if (!$attendance) {
                        $attendance = new Attendance();
                        $attendance->user_id = $user->id;
                        $attendance->date = date('Y-m-d');
                        $attendance->save();
                    }
                }
            }
        }
        else{
            $this->info('Today is holiday or weekend');
        }
    }
}
