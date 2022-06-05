<?php

namespace App\Traits;

use App\Models\Attendance;
use App\Models\Salary\SalaryAdjustment as SalarySalaryAdjustment;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

trait SalaryAdjustment
{

    public function calculateAdjustment($adjustment, $salary)
    {
        $amount = $adjustment->amount ? $adjustment->amount : $adjustment->percent * $salary;
        $salary = $salary + $amount;
        return $salary;
    }



}
