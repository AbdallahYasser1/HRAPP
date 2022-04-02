<?php

namespace App\Http\Controllers\Salary;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Salary\SalaryTerm;
use Illuminate\Http\Request;

class TermSlipController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SalaryTerm $salaryTerm)
    {
        $slips = $salaryTerm->slips;
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Salary\SalaryTerm  $salaryTerm
     * @return \Illuminate\Http\Response
     */
    public function show(SalaryTerm $salaryTerm)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Salary\SalaryTerm  $salaryTerm
     * @return \Illuminate\Http\Response
     */
    public function edit(SalaryTerm $salaryTerm)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Salary\SalaryTerm  $salaryTerm
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SalaryTerm $salaryTerm)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Salary\SalaryTerm  $salaryTerm
     * @return \Illuminate\Http\Response
     */
    public function destroy(SalaryTerm $salaryTerm)
    {
        //
    }
}
