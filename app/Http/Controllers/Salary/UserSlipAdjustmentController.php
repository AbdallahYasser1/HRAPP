<?php

namespace App\Http\Controllers\Salary;

use Illuminate\Http\Request;
use App\Models\Salary\SalarySlip;
use App\Http\Controllers\ApiController;
use App\Models\Salary\SalaryAdjustment;
use App\Models\Salary\SalaryAdjustmentType;
use App\Models\User;
use App\Traits\Utils;
use Illuminate\Support\Facades\Auth;

class UserSlipAdjustmentController extends ApiController
{
    use Utils;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($slip_id)
    {
        $adjustments = SalarySlip::find($slip_id)->adjustments;
        return  $this->showAll($adjustments);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $slip_id)
    {
        $rules = [
            'salary_adjustment_type_id' => 'required|integer',


        ];
        $this->validate($request, $rules);

        $data = $request->all();
        $data['salary_slip_id'] = $slip_id;

        try {

            $data['title'] = SalaryAdjustmentType::find($data['salary_adjustment_type_id'])->name;

        } catch (\Exception $e) {

            return $this->errorResponse("There is no adjustment with this identifier",400);
        }


        $adjustment = SalaryAdjustment::create($data);
        return $this->showOne($adjustment);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slip_id, $adjustment_id)
    {
        $adjustment = SalarySlip::find($slip_id)->adjustments->find($adjustment_id);
        return $this->showOne($adjustment);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($user_id, $slip_id, $adjustment_id)
    {
        $adjustment = SalaryAdjustment::find($adjustment_id);
        $adjustment->delete();
        return $this->showOne($adjustment);
    }

    public function lastSlipAdjustments($user_id) {
        $user = User::find($user_id);
        $slip = $user->lastSlip;
        $adjustments = $slip->adjustments;
        return $this->showAll($adjustments);
    }

    public function getLastSlipDeductions()
    {
        $user = Auth::user();
        $salarySlip = $user->lastSlip;
        $adjustments = $salarySlip->adjustments->where('amount', '<', 0);
        return  $this->showAll($adjustments);
    }

    public function getLastSlipEarnings()
    {
        $user = Auth::user();
        $salarySlip = $user->lastSlip;
        $adjustments = $salarySlip->adjustments->where('amount', '>', 0);
        return  $this->showAll($adjustments);
    }

    public function getSlipDeductions($slip_id)
    {
        $salarySlip = SalarySlip::find($slip_id);
        $user = Auth::user();

        $checkUser = $this->checkUser($user, $salarySlip);
        if ($checkUser)
            return $checkUser;

        $adjustments = $salarySlip->adjustments->where('amount', '<', 0);
        return  $this->showAll($adjustments);
    }

    public function getSlipEarnings($slip_id)
    {
        $salarySlip = SalarySlip::find($slip_id);
        $user = Auth::user();
        $checkUser = $this->checkUser($user, $salarySlip);
        if ($checkUser)
            return $checkUser;

        $adjustments = $salarySlip->adjustments->where('amount', '>', 0);
        return  $this->showAll($adjustments);
    }

    public function checkUser(User $user, SalarySlip $salarySlip)
    {
        if ($user->id != $salarySlip->user_id) {
            return $this->errorResponse("This user does not have access to this salary slip", 400);
        }
    }
}
