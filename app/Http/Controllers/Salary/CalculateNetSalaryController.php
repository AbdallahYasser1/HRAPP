<?php

namespace App\Http\Controllers\Salary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Salary\SalarySlip;
use App\Http\Controllers\ApiController;
use App\Models\Salary\SalaryTerm;
use App\Models\User;
use App\Traits\CalcNetSalary;
use App\Traits\SalaryAdjustment;
use Illuminate\Support\Facades\Auth;

class CalculateNetSalaryController extends ApiController
{
    use CalcNetSalary;
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function calculateUserSlip($user_id, $slip_id)
    {
        $user = User::findOrFail($user_id);
        $salarySlip = $user->salarySlips()->findOrFail($slip_id);
        $slip = $this->calculateSlip($salarySlip);
        return $this->showOne($slip);
    }

    public function calculateMySlip($slip_id)
    {
        $user = Auth::user();
        $salarySlip = $user->salarySlips()->findOrFail($slip_id);
        $slip = $this->calculateSlip($salarySlip);
        return $this->showOne($slip);
    }

    public function calcMyLastSlip()
    {
        $user = Auth::user();
        $slip = $user->lastSlip;
        if ($slip) {
            $slip = $this->calculateSlip($slip);
            return $this->showOne($slip);
        } else {
            return $this->errorResponse('No salary slip found', 404);
        }
    }



    public function calcSlip($slip_id)
    {
        $salarySlip = SalarySlip::findOrFail($slip_id);

        $slip = $this->calculateSlip($salarySlip);
        return $this->showOne($slip);
    }
}
