<?php

namespace App\Http\Controllers;
use App\Models\Requestdb;
use App\Models\User;
use App\Models\Wfh;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class SupervisorController extends ApiController
{
    public function showSupervisedUsers(){
 $employees=User::whereHas('supervisor', function($query)
 {
     $query->where('supervisor', '=', Auth::id());})->get();

        return $this->showCustom($employees,200);
    }

    public function showSupervisedUsersPendingRequests(){

        $employees= DB::table('requestdbs')
                    ->join('wfhs','requestdbs.requestable_id','wfhs.id')
                    ->join('users','requestdbs.user_id','users.id')
                   ->where('users.supervisor','=',Auth::id())
                    ->where('requestdbs.status','=','pending')
                    ->get();

        return $this->showCustom($employees,200);
    }
}
