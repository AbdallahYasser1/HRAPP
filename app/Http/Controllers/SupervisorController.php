<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupervisorController extends ApiController
{
    public function showSupervisedUsers(){
 $employees=User::whereHas('supervisor', function($query)
 {
     $query->where('supervisor', '=', Auth::id());})->get();

        return $this->showCustom($employees,200);
    }
}
