<?php

namespace App\Http\Controllers\Salary;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Salary\SalaryAdjustmentType;
use Illuminate\Http\Request;

class SalaryAdjustmentTypeController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {$types = SalaryAdjustmentType::all();
        return $this->showAll($types);

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
        $rules = [
            'name' => 'required|string|max:255',
            'isAll' => 'required|boolean',

        ];

        $this->validate($request, $rules);

        $data = $request->all();

        $type = SalaryAdjustmentType::create($data);

        return $this->showOne($type, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Salary\SalaryAdjustmentType  $salaryAdjustmentType
     * @return \Illuminate\Http\Response
     */
    public function show(SalaryAdjustmentType $salaryAdjustmentType)
    {
        return $this->showOne($salaryAdjustmentType);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Salary\SalaryAdjustmentType  $salaryAdjustmentType
     * @return \Illuminate\Http\Response
     */
    public function edit(SalaryAdjustmentType $salaryAdjustmentType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Salary\SalaryAdjustmentType  $salaryAdjustmentType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SalaryAdjustmentType $salaryAdjustmentType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Salary\SalaryAdjustmentType  $salaryAdjustmentType
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $salaryAdjustmentType = SalaryAdjustmentType::findOrFail($id);
        $salaryAdjustmentType->delete();
        return $this->showOne($salaryAdjustmentType);
    }
}
