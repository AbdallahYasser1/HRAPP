<?php

namespace App\Console\Commands;

use App\Models\Requestdb;
use Illuminate\Console\Command;
use App\Models\Holiday;
use App\Models\Attendance;
use App\Models\Absence;
use App\Models\Wfh;
use App\Traits\Utils;
use App\Models\User;

class AbsentEmployee extends Command
{
    use Utils;
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
     */
    public function handle()
    {


        $attendances = Attendance::where('date', date('Y-m-d'))->get();
        foreach ($attendances as $attendance) {
            if ($attendance->start_time == null && $attendance->end_time == null) {
                $user_id = $attendance->user_id;
                $date = $attendance->date;
                $absence = new Absence();
                $absence->user_id = $user_id;
                $absence->date = $date;

                $wfhs = Requestdb::all()
                    ->where('user_id', $user_id)
                    ->where('start_date', date('Y-m-d'))
                    ->where('requestable_type', '=', "App\\Models\\Wfh")
                    ->whereIn('status', ['in-progress', 'approved']);

                $isUserWFH = !$wfhs->isEmpty();
                if ($isUserWFH)
                    $absence->status = 'wfh';
                else
                    $absence->status = 'absent';

                $absence->save();
                $attendance->delete();
            } elseif ($attendance->start_time == null && $attendance->end_time != null) {
                $user_id = $attendance->user_id;
                $date = $attendance->date;
                $absence = new Absence();
                $absence->user_id = $user_id;
                $absence->date = $date;
                $user = User::find($user_id);
                $isUserLeave = $this->CheckLeave($user)->isEmpty();
                if (!$isUserLeave)
                    $absence->status = 'leave';
                else
                    $absence->status = 'absent';

                $absence->save();
                $attendance->delete();
            } elseif ($attendance->start_time != null && $attendance->end_time == null) {
                $user_id = $attendance->user_id;
                $date = $attendance->date;
                $absence = new Absence();
                $absence->user_id = $user_id;
                $absence->date = $date;
                $user = User::find($user_id);
                $isUserLeave = $this->CheckLeave($user)->isEmpty();
                if (!$isUserLeave)
                    $absence->status = 'leave';
                else
                    $absence->status = 'absent';
                $absence->save();
                $attendance->delete();
            }
        }
    }
}
