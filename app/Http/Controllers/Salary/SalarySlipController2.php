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
        $salarySlip = SalarySlip::find($id);
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
}
