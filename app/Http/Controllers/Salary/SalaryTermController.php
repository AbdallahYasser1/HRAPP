<?php

namespace App\Http\Controllers\Salary;

use App\Http\Controllers\ApiController;
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
    public function store(Request $request, $user_id)
    {
        $user = User::find($user_id);
        $rules = [
            'salary_agreed' => 'required|numeric',
            'start' => 'required|date',
            'end' => 'required|date',
        ];

        $this->validate($request, $rules);

        $data = $request->all();
        $data['user_id'] = $user->id;

        $term = SalaryTerm::create($data);

        return $this->showOne($term, 201);
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
    public function update(Request $request, $salaryTerm_id)
    {
        $salaryTerm = SalaryTerm::find($salaryTerm_id);
        $salaryTerm->fill($request->only([
            'salary_agreed',
            'start',
            'end',
        ]));

        if ($salaryTerm->isClean()) {
            return $this->errorResponse('At least one value must change', 422);
        }

        $salaryTerm->save();

        return $this->showOne($salaryTerm);
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
