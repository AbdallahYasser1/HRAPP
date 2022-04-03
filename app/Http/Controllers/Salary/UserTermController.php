<?php

namespace App\Http\Controllers\Salary;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Salary\SalaryTerm;
use App\Models\User;
use Illuminate\Http\Request;

class UserTermController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(User $user)
    {
        $term = $user->salaryTerm;
        return $this->showOne($term);
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
//            'start' => 'required|date|date_format:Y-m-d|after:yesterday',
//            'end' => 'required|date|date_format:d-m-Y',
            'salary_agreed' => 'required|integer',

        ];
        $this->validate($request, $rules);

        $data = $request->all();
        $data['user_id'] = $id;

        $newTerm = SalaryTerm::create($data);
        return $this->showOne($newTerm, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
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
    public function update(Request $request, User $user, $salaryTermId)
    {
//        $user = User::find($id);
        $term = SalaryTerm::find($salaryTermId);

        $rules = [
            'salary_agreed' => 'integer',
        ];
        $this->validate($request, $rules);
        $this->checkUser($user, $term);

        $term->fill($request->only(['salary_agreed', 'end']));
        if($term->isClean()) {
            return $this->errorResponse('you need to specify a different value to update', 422);
        }

        $term->save();
        return $this->showOne($term);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }



    public function checkUser(User $user, SalaryTerm $term)
    {
        if ($user->id != $term->user_id) {
            throw new HttpException(422, 'The specified seller is not the actual seller of the product');
        }
    }
}
