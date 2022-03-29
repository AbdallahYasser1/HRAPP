<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
 use App\Models\Job_Title;
class JobTitleController extends ApiController
{
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
    public function destroy($id)
    {
        $jobtitles = Job_Title::find($id);
        if ($jobtitles === null) {
            return $this->errorResponse("Job Title not found", 404);
        } else {
            $jobtitles->delete();
            return $this->showCustom(['Job Title deleted'], 200);
        }
    }
}
