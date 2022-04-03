<?php

namespace App\Http\Controllers\Salary;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Salary\SalaryAdjustment;
use App\Models\Salary\SalaryAdjustmentType;
use App\Models\Salary\SalarySlip;
use Illuminate\Http\Request;

class SlipAdjustmentController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $salarySlip = SalarySlip::find($id);
        $adjustments = $salarySlip->adjustments;
        return  $this->showAll($adjustments);
//    return $salarySlip;
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
    public function store(Request $request, $id)
    {
        $rules = [
            'amount' => 'required|integer',
            'percent' => 'required|integer',


        ];
        $this->validate($request, $rules);
        $salarySlip = SalarySlip::find($id);

        $data = $request->all();
        $data['salary_slip_id'] = $salarySlip->id;
        $data['salary_adjustment_type_id'] = SalaryAdjustmentType::all()->random()->id;
        $adjustment = SalaryAdjustment::create($data);
        return $this->showOne($adjustment);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Salary\SalarySlip  $salarySlip
     * @return \Illuminate\Http\Response
     */
    public function show(SalarySlip $salarySlip)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Salary\SalarySlip  $salarySlip
     * @return \Illuminate\Http\Response
     */
    public function edit(SalarySlip $salarySlip)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Salary\SalarySlip  $salarySlip
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SalarySlip $salarySlip)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Salary\SalarySlip  $salarySlip
     * @return \Illuminate\Http\Response
     */
    public function destroy(SalarySlip $salarySlip)
    {
        //
    }
}
