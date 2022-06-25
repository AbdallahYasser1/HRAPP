<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Models\Salary\SalarySlip;
use App\Models\Salary\SalaryTerm;

trait CalcNetSalary 
{
    use SalaryAdjustment;
    use ApiResponser;
    public function calculateSlip($salarySlip)
    {
        
        if (!$salarySlip) {
            return $this->errorResponse('No salary slip found', 404);
        }
        $term = SalaryTerm::find($salarySlip->salary_term_id);

        $salary = $term->salary_agreed;
        $adjustments = $salarySlip->adjustments;

        $adjustments->each(function ($adjustment) use (&$salary) {
            $salary = $this->calculateAdjustment($adjustment, $salary);
        });
        $salarySlip->net_salary = $salary;

        $salarySlip->save();

        return $salarySlip;
    }

}
