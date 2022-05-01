<?php

namespace App\Traits;

use App\Models\Attendance;
use App\Models\Salary\SalaryAdjustment as SalarySalaryAdjustment;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

trait SalaryAdjustment
{

    static $halfDayDeduction = 1.665;
    static $fullDayDeduction = 3.333;

    public function getHalfDay($user)
    {
        $shift = $user->shift;
        $end_hour = date('h', strtotime($shift->end_time));
        $start_hour = date('h', strtotime($shift->start_time));
        $half_day = ($end_hour - $start_hour) / 2;
        return $half_day;
    }
    
    public function makeHalfDayAdjustment($user, $half_day) {
        $attendances = $user->attendances;
        $count = 0;
        foreach($attendances as $attendance) {
            $attendance_hours =  date('h', strtotime($attendance->leave_time)) -  date('h', strtotime($attendance->start_time));
            if($attendance_hours <= $half_day) {
                $count++;
                SalaryAdjustment::create([
                    'salary_adjustment_type_id' => 1,
                    'amount' => $user->term->salary_agreed * SalaryAdjustment::$halfDayDeduction,
                    'salary_slip_id' => $user->lastSlip->id,
                    'date' => $attendance->date,
                ]);
            }
        }

        $adjustment = $count * self::$halfDayDeduction;
        return $adjustment;
    }

    public function addHalfDayAdjustment($user_id)
    {
        $user = User::find($user_id);
        $half_day = $this->getHalfDay($user);
        $adjustment = $this->makeHalfDayAdjustment($user, $half_day);
    }

    public function makeFullDayAdjustment($id, $full_day) {
        $user = User::find($id);
        $attendances = Attendance::where('user_id', $id)->get();
        $count = 0;
        foreach($attendances as $attendance) {
            $attendance_hours =  date('h', strtotime($attendance->leave_time)) -  date('h', strtotime($attendance->start_time));
            if($attendance_hours >= $full_day) {
                $count++;
                SalaryAdjustment::create([
                    'salary_adjustment_type_id' => 2,
                    'amount' => $user->term->salary_agreed * SalaryAdjustment::$fullDayDeduction,
                    'salary_slip_id' => $user->lastSlip->id,
                    'date' => $attendance->date,
                ]);
            }
        }

        $adjustment = $count * self::$fullDayDeduction;
        return $adjustment;
    }

    public function calculateAdjustment($adjustment, $salary)
    {
        $amount = $adjustment->amount ? $adjustment->amount : $adjustment->percent * $salary;
        
        $salary = $salary + $amount;
        return $salary;
    }
}
