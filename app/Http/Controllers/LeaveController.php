<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends RequestController
{
    public function store(Request $request)
    {
        $this->CheckRequestDate($request['start_date']);
        $shift = User::find(Auth::id())->shift()->first();
        $timeShift = date('H', strtotime($shift->start_time));
        $endshift = date('H', strtotime($shift->midday));
        $now = date('H', time());
        $timeOfMission = date('Y-m-d', strtotime($request['start_date']));
        $timeOfDate = date('Y-m-d', time());
        if ($request['leave_time']=='first_half'){
            if ($timeOfMission == $timeOfDate && !($timeShift - $now > 0)) {
                return $this->errorResponse("cant make request in past date", 400);
            }
        } else{
            if ($timeOfMission == $timeOfDate && !($endshift - $now > 0)) {
                return $this->errorResponse("cant make request in past date", 400);
            }
        }
        $leave = new Leave;
        $leave->description=$request['description'];
        $leave->leave_time=$request['leave_time'];
        $leave->save();
        $requestdb=$this->Create_Request($request);
        $requestdb->end_date = $request['start_date'];
        $leave->requests()->save($requestdb);
        $response = ["message" => "Leave Request Has Succesfully created", "Request" => $requestdb,"Leave"=>$leave];
        return $this->showCustom($response, 201);
    }
    public function ShowLeave(Leave $leave)
    {
        if ($leave === null)
            return $this->errorResponse("Leave Request not found", 404);
        if($leave->id!=Auth::id() || ! Auth::user()->hasPermissionTo('Show_Task_Request'||!$leave->employees()->where('user_id',Auth::id()))){
            $this->errorResponse("You do not have the permission",403);
        }
        return $this->showOne($leave,200);
    }
    public function ShowAllLeaves()
    {
        return $this->ShowAllUserRequests(Leave::class);
    }

    public function update(Request $request, Leave $leave)
    {
        if ($leave === null) {
            return $this->errorResponse("Task is not found", 404);
        } else {
            if ($leave->requests->first()->user_id == Auth::id()) {
                $leave->update($request->all());
                return $this->showCustom($leave->requests->first(),200);
            } else {
                return $this->errorResponse("You don't have the permision to update", 401);
            }
        }
    }
    public function CancelLeave(Leave $leave)
    {
        return $this->CancelRequest($leave);
    }
}
