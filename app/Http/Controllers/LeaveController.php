<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeaveRequest;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends RequestController
{
    public function store(LeaveRequest $request)
    {
        $this->CheckRequestDate($request['start_date']);
        $shift = User::find(Auth::id())->shift()->first();
        $timeShift = date('H', strtotime($shift->start_time));// start time of shift
        $endshift = date('H', strtotime($shift->midday)); // mid time of shift
        $now = date('H', time()); // time now
        $timeOfMission = date('Y-m-d', strtotime($request['start_date'])); // sent date
        $timeOfDate = date('Y-m-d', time()); // date now
        if ($request['leave_time']=='first_half'){
            if ($timeOfMission == $timeOfDate && !($timeShift - $now > 0)) {
                //compare if date is today and start time of shift - time now is not bigger than 0
                // means that the shift time started
                return $this->errorResponse("cant make request in past date", 400);
            }
        } else{ // second half
            if ($timeOfMission == $timeOfDate && !($endshift - $now > 0)) {
                // check time of midday hasnt came
                return $this->errorResponse("cant make request in past date", 400);
            }
        }
        $leave = new Leave;
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
        $leave->requests()->get();
        return $this->showCustom(["leave"=>$leave,"request"=>  $leave->requests()->get()],200);
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
