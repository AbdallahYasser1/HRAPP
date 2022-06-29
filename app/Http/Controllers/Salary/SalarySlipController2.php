<?php

namespace App\Http\Controllers\Salary;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Salary\SalarySlip;
use Illuminate\Http\Request;

class SalarySlipController2 extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $slips = SalarySlip::all();
        $adjustments = $slips->map(function ($slip) {
            return $slip->adjustments;
        });
        return $this->showAll($slips);
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
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Salary\SalarySlip  $salarySlip
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $salarySlip = SalarySlip::findOrFail($id);
        $adjustments = $salarySlip->adjustments;
        return $this->showOne($salarySlip);

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

    public function getSlipsByMonth(Request $request)
    {
        $rules = [
            'date' => 'required|regex:/^\d{4}-\d{2}$/',
        ];
        $this->validate($request, $rules);

        $slips = SalarySlip::where('date', 'like', '%' . $request['date'] . '%')->get();
        $adjustments = $slips->map(function ($slip) {
            return $slip->adjustments;
        });
        return $this->showAll($slips);
    }
}
