<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
 use App\Models\Job_Title;
class JobTitleController extends ApiController
{
    public function index()
    {
        $jobtitles = Job_Title::all();
        if ($jobtitles === null) {
            return $this->errorResponse("JobTitles is not exist", 404);
        } else {
            return $this->showAll($jobtitles, 200);
        }}
    public function store(Request $request){
        $job_title=Job_Title::create(['job_name'=>$request['name'],
            'department_id'=>$request['department_id']]);

        return $this->showCustom($job_title, 201);
    }
    public function update(Request $request, $id)
    {
        $job_titles = Job_Title::find($id);
        if ($job_titles === null) {
            return $this->errorResponse("Department not found", 404);
        } else {
            $job_titles->update([
                'name' => $request['name'],
                'department_id'=>$request['department_id']==null?$job_titles->department_id : $request['department_id']
            ]);
            return $this->showOne($job_titles, 200);
        }
    }
    public function destroy(Job_Title $job_Title)
    {

        if ($job_Title === null) {
            return $this->errorResponse("Job Title not found", 404);
        } else {
            $job_Title->delete();
            return $this->showCustom('Job Title deleted', 200);
        }
    }
}
