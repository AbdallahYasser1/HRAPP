<?php

namespace App\Http\Controllers\Salary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Salary\SalarySlip;
use App\Http\Controllers\ApiController;
use App\Traits\SalaryAdjustment;

class CalculateNetSalaryController extends ApiController
{
    use SalaryAdjustment;
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, $user_id, $slip_id)
    {
        $this->addHalfDayAdjustment($user_id);

        $salarySlip = SalarySlip::find($slip_id);
        $salary = $salarySlip->term->salary_agreed;
        $adjustments = $salarySlip->adjustments;
        $adjustments->each(function ($adjustment) use (&$salary) {
            $salary = $this->calculateAdjustment($adjustment, $salary);
        });
        $salarySlip->net_salary = $salary;
        $salarySlip->save();
        return $this->showOne($salarySlip);
    }
}
