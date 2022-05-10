<?php

namespace App\Traits;

use App\Models\Attendance;
use App\Models\Salary\SalaryAdjustment as SalarySalaryAdjustment;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

trait SalaryAdjustment
{

    static $halfDayDeduction = -0.01665;
    static $fullDayDeduction = -0.03333;

    public function getHalfDay($user)
    {
        $shift = $user->shift;
        $end_hour = date('H', strtotime($shift->end_time));
        $start_hour = date('H', strtotime($shift->start_time));
        $half_day = ($end_hour - $start_hour) / 2;
        return $half_day;
    }

    public function makeHalfDayAdjustment($user, $half_day) {
        // handle one month only
        $attendances = $user->attendances;
        $count = 0;
        foreach($attendances as $attendance) {
            if(!$attendance->leave_time)
                continue;
            $attendance_hours =  date('H', strtotime($attendance->leave_time)) -  date('H', strtotime($attendance->start_time));
            if($attendance_hours <= $half_day) {
                $count++;
                SalarySalaryAdjustment::create([
                    'salary_adjustment_type_id' => 1,
                    'amount' => $user->salaryTerm->salary_agreed * SalaryAdjustment::$halfDayDeduction,
                    'salary_slip_id' => $user->lastSlip->id,
                    'date' => $attendance->date,
                ]);
            }
        }

    }

    public function addHalfDayAdjustment($user_id)
    {
        $user = User::find($user_id);
        $half_day = $this->getHalfDay($user);
        $adjustment = $this->makeHalfDayAdjustment($user, $half_day);
    }

    public function makeFullDayAdjustment($id) {
        // only one month
        $user = User::find($id);
        $attendances = Attendance::where('user_id', $id)->get();
        $count = 0;
        $notVacation = true;
        foreach($attendances as $attendance) {
            if(!$attendance->start_time && $notVacation) {
                $count++;
                SalarySalaryAdjustment::create([
                    'salary_adjustment_type_id' => 2,
                    'amount' => $user->salaryTerm->salary_agreed * SalaryAdjustment::$fullDayDeduction,
                    'salary_slip_id' => $user->lastSlip->id,
                    'date' => $attendance->date,
                ]);
            }
        }
    }

    public function calculateAdjustment($adjustment, $salary)
    {
        $amount = $adjustment->amount ? $adjustment->amount : $adjustment->percent * $salary;

        $salary = $salary + $amount;
        return $salary;
    }
}
