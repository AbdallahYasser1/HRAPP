<?php

namespace App\Http\Controllers;

use App\Models\Requestdb;
use App\Models\User;
use App\Models\Vacation;
use App\Models\Vacationday;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class VacationController extends RequestController
{
    public function VacationBalance($type){
        if($type=='scheduled')
  $days=Auth::user()->vacationday->scheduled;
        else
            $days=Auth::user()->vacationday->unscheduled;

return $days;
    }
    public function CheckVacationBalance($type){

  $vacationbalance=$this->VacationBalance($type);
$takendays= Requestdb::with(['requestable'])
    ->join('users', 'requestdbs.user_id', 'users.id')
    ->where('requestdbs.status', '=', "pending")
    ->join('vacations','requestdbs.requestable_id','vacations.id')
    ->where("user_id",'=',Auth::id())
    ->where('requestdbs.requestable_type','=',"App\\Models\\".ucwords("vacation"))
    ->where('type',$type)
    ->sum('count');
return $vacationbalance>=$takendays;

    }
    public function VacationDuration(Request $request){ // try with 2 variables better
        if(Carbon::parse(date('Y-m-d', strtotime($request['end_date'])))<Carbon::parse(date('Y-m-d', strtotime($request['start_date'])))) return $this->errorResponse('invalid data',400);
        $from_date = Carbon::parse(date('Y-m-d', strtotime($request['start_date'])));
        $through_date = Carbon::parse(date('Y-m-d', strtotime($request['end_date'])));
        $years = $from_date->diffInYears($through_date); // check vacation in the same year (start-end)
        $yearnow = Carbon::parse(date('Y-m-d', strtotime(Carbon::today())))->year; // check vacation in the same year(now - start)
        $requestyear =  $from_date->year;// check vacation in the same year(now - start)
        if($years>0 || $yearnow<$requestyear) return $this->errorResponse("Can't make vacation next year",400);
        $duration = ($from_date->diffInDays($through_date))+1;
        error_log('duration '.$duration);
    return $duration;
    }
    public function store(Request $request)
    {
        // Check Vacation balance
        $vacation = new Vacation();
        $vacation->type=$request['type'];
        // if vacation was unscheduled then it should be at time in past
        // so it removes any salary deducation
        $vacation->save();
        $requestdb = new Requestdb;
        $requestdb->user_id = Auth::id();
        $requestdb->start_date = $request['start_date'];
        $requestdb->end_date = $request['end_date'];
        $vacation->requests()->save($requestdb);
        $response = ["message" => "Vacation Request Succesfully created", "Request" => $requestdb];
        error_log($vacation);
        return $this->successResponse($response, 201);
    }
}
