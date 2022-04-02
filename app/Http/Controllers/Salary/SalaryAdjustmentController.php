<?php

namespace App\Http\Controllers\Salary;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Salary\SalaryAdjustment;
use Illuminate\Http\Request;

class SalaryAdjustmentController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $adjustments = SalaryAdjustment::all();
        return $this->showAll($adjustments);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Salary\SalaryAdjustment  $salaryAdjustment
     * @return \Illuminate\Http\Response
     */
    public function show(SalaryAdjustment $salaryAdjustment)
    {
        return $this->showOne($salaryAdjustment);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Salary\SalaryAdjustment  $salaryAdjustment
     * @return \Illuminate\Http\Response
     */
    public function edit(SalaryAdjustment $salaryAdjustment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Salary\SalaryAdjustment  $salaryAdjustment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SalaryAdjustment $salaryAdjustment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Salary\SalaryAdjustment  $salaryAdjustment
     * @return \Illuminate\Http\Response
     */
    public function destroy(SalaryAdjustment $salaryAdjustment)
    {
        //
    }
}
