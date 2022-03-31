<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShiftRequest;
use App\Http\Resources\ShiftCollection;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;

class DepartmentController extends ApiController
{
    public function index()
    {
        $department = Department::all();
        if ($department === null) {
            return $this->errorResponse("Departments is not exist", 404);
        } else {
            return $this->showAll($department, 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $department = Department::create([
            'name' => $request['name']
        ]);

        return $this->showCustom($department, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showJobTitles(Department $department)
    {
        $jobtitles =$department->job_titles;

        if ($jobtitles === null) {
            return $this->errorResponse("Department not found", 404);
        } else {
            return $this->showCustom($jobtitles, 200);
        }
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
        $department = Department::find($id);
        if ($department === null) {
            return $this->errorResponse("Department not found", 404);
        } else {
            $department->update([
                'name' => $request['name'],
                ]);
            return $this->showOne($department, 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $department = Department::find($id);
        if ($department === null) {
            return $this->errorResponse("Department not found", 404);
        } else {
            $department->delete();
            return $this->showCustom(['Department deleted'], 200);
        }
    }

    public function getUsersOfDepartment(Department $department)
    {
$users=$department->with('profile.user')->get();

        if ($users === null) {
            return $this->errorResponse("users not found", 404);
        }else{

            return $this->showCustom($users,200);
        }

    }

}
