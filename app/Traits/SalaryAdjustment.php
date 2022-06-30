<?php

namespace App\Traits;

use App\Models\Salary\SalaryAdjustmentType;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

trait SalaryAdjustment
{
    use ApiResponser;
    public function calculateAdjustment($adjustment, $salary)
    {
        $adjustment_type_amount = SalaryAdjustmentType::find($adjustment->salary_adjustment_type_id)->amount;
        $adjustment_type_percent = SalaryAdjustmentType::find($adjustment->salary_adjustment_type_id)->percent;
        

        if($adjustment->amount) {
            $amount = $adjustment->amount;
        } elseif($adjustment->percent) {
            $amount = $adjustment->percent * $salary;
        } elseif ($adjustment_type_amount) {
            $amount = $adjustment_type_amount;
        } elseif ($adjustment_type_percent) {
            $amount = $adjustment_type_percent * $salary;
        } else {
            return $this->errorResponse('No amount or percent specified for adjustment', 404);
        }

        $adjustment->amount = $amount;
        $salary = $salary + $amount;
        return $salary;
    }



}
