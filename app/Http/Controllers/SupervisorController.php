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
        $employees = User::whereHas('supervisor', function ($query) {
            $query->where('supervisor', '=', Auth::id());
        })->get();

        return $this->showCustom($employees, 200);
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
    public function supervisorApproveRequest(Request $request,$id){
        $requestdb=Requestdb::find($id);
        if ($requestdb === null) {
            return $this->errorResponse("User is not existed", 404);
        } else {
            if($request->user()->supervisor==Auth::id()){
                $requestdb->status = $request['status'];
                return $this->showCustom($requestdb, 200);
            }else{
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
            ->get();

        return $this->showCustom($employees, 200);
    }
}
