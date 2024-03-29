<?php

namespace App\Http\Controllers\Salary;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Salary\SalarySlip;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserSlipController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // var_dump("here");

        $user = Auth::user();
        // var_dump($user);
        $slips = $user->salarySlips;
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
    public function store(Request $request, $id)
    {
        $user = User::find($id);

        $rules = [
            'date' => 'required|string',
        ];
        $this->validate($request, $rules);

        $data = $request->all();
        $data['user_id'] = $user->id;
        $data['user_name'] = $user->name;
        $data['salary_term_id'] = $user->salaryTerm->id;
        $slip = SalarySlip::create($data);
        return $this->showOne($slip);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();
        $slip = $user->salarySlips()->findOrFail($id);
        $adjustments = $slip->adjustments;
        return $this->showOne($slip);
    }


    public function getUserSlips($id)
    {
        $user = User::findOrFail($id);
        $slips = $user->salarySlips;
        $adjustments = $slips->map(function ($slip) {
            return $slip->adjustments;
        });
        return $this->showAll($slips);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user, $slip_id)
    {
        $user = Auth::user();

        $slip = $user->salarySlips()->findOrFail($slip_id);
        // $rules = [
        //     'date' => 'required|string',
        // ];
        // $this->validate($request, $rules);

        $data = $request->all();
        $slip->update($data);
        return $this->showOne($slip);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user, $slip_id)
    {
        // $user= Auth::user();

        $slip = $user->salarySlips()->findOrFail($slip_id);
        $slip->delete();
        return $this->showOne($slip);
    }

    public function lastSlip($id)
    {
        $user = User::find($id);
        $slip = $user->lastSlip;
        $adjustments = $slip->adjustments;
        return $this->showOne($slip);
    }

    public function updateLastSlip(Request $request, $id)
    {
        $user = User::find($id);
        $slip = $user->lastSlip;

        // $rules = [
        //     'net_salary' => 'required|integer',
        //     'period' => 'required|string',
        // ];
        // $this->validate($request, $rules);

        $slip->fill($request);
        if ($slip->isClean()) {
            return $this->errorResponse('you need to specify a different value to update', 422);
        }

        $slip->save();
        $adjustments = $slip->adjustments;
        return $this->showOne($slip);
    }

    public function destroyLastSlip($id)
    {
        $user = User::find($id);
        $slip = $user->lastSlip;
        $slip->delete();
        return $this->showOne($slip);
    }

    public function getSlipByMonth(Request $request)
    {
        $rules = [
            'date' => 'required|regex:/^\d{4}-\d{2}$/',
        ];
        $this->validate($request, $rules);

        $user = Auth::user();
        $slip = $user->salarySlips()->where('date', 'like', '%' . $request['date'] . '%')->get()->first();
        $adjustments = $slip->adjustments;
        return $this->showOne($slip);
    }
}
