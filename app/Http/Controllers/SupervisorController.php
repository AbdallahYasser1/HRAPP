<?php

namespace App\Http\Controllers;

use App\Models\Requestdb;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupervisorController extends ApiController
{
    public function showSupervisedUsers()
    {
        $employees = User::where("supervisor", '=', Auth::id())
            ->join('profiles','users.id','profiles.user_id')
            ->join('job__titles','profiles.job__title_id','job__titles.id')
            ->join('departments','profiles.department_id','departments.id')
            ->select('users.id as id','users.name','users.email','profiles.image','users.status','job__titles.job_name as job_title','users.active','departments.name as department ')
            ->get();
        if ($employees->count()==0) {
            return $this->errorResponse("You are not assigned to users at this moment", 200);
        } else {
            return $this->showCustom($employees, 200);
        }
    }
    public function makeUserSupervised(Request $request)
    {

        $user = User::find($request['id']);
        if ($user === null) {
            return $this->errorResponse("User is not existed", 404);
        } else {
            $user->supervisor = $request['supervisor'];
            return $this->showCustom($user, 200);
        }
    }
    public function supervisorApproveRequest(Request $request, $id)
    {
        $requestdb = Requestdb::find($id);
        if ($requestdb === null) {
            return $this->errorResponse("User is not existed", 404);
        } else {
            //print_r(Auth::id());
            if ($requestdb->user->supervisor == Auth::id()) {
                $requestdb->status = $request['status'];
                $requestdb->bywhom =Auth::id();
                $requestdb->save();
                return $this->showCustom($requestdb, 200);
            } else {
                return $this->errorResponse("Not Auth User", 401);
            }
        }
    }
    public function showSupervisedUsersPendingRequests()
    {
        $employees = Requestdb::with(['requestable'])
            ->join('users', 'requestdbs.user_id', 'users.id')
            ->where('supervisor', '=', Auth::id())
            ->where('requestdbs.status', '=', 'pending')
          ->select('users.id as user_id','name','requestdbs.requestable_id','requestdbs.status','requestdbs.requestable_type','requestdbs.start_date','requestdbs.end_date')
            ->get();
            if ($employees->count()==0) {
                return $this->errorResponse("There is no pending requests", 404);
            } else {
                return $this->showCustom($employees, 200);
            }
    }
}
