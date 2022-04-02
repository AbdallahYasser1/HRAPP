<?php

namespace App\Http\Controllers\Salary;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Salary\SalarySlip;
use App\Models\Salary\SalaryTerm;
use App\Models\User;
use Illuminate\Http\Request;

class SalaryTermController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $terms = SalaryTerm::all();
        return $this->showAll($terms);
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
    public function store(Request $request, User $user)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Salary\SalaryTerm  $salaryTerm
     * @return \Illuminate\Http\Response
     */
    public function show(SalaryTerm $salaryTerm)
    {
        return $this->showOne($salaryTerm);

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
