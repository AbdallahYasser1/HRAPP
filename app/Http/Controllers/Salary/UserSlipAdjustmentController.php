<?php

namespace App\Http\Controllers\Salary;

use Illuminate\Http\Request;
use App\Models\Salary\SalarySlip;
use App\Http\Controllers\ApiController;
use App\Models\Salary\SalaryAdjustment;

class UserSlipAdjustmentController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($user_id, $slip_id)
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
    public function store(Request $request, $user_id, $slip_id)
    {
        $rules = [
            'amount' => 'required|numeric',
            'salary_adjustment_type_id' => 'required|integer',


        ];
        $this->validate($request, $rules);

        $data = $request->all();
        $data['salary_slip_id'] = $slip_id;
        $adjustment = SalaryAdjustment::create($data);
        return $this->showOne($adjustment);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($user_id, $slip_id, $adjustment_id)
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
}
