<?php

namespace App\Http\Controllers;

use App\Http\Requests\VacationRequest;
use App\Models\Absence;
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
    public function takendays($type){
        $yearnow = Carbon::parse(date('Y-m-d', strtotime(Carbon::today())))->year;
        $takendays= Requestdb::with(['requestable'])
            ->join('users', 'requestdbs.user_id', 'users.id')
            ->whereIn('requestdbs.status', ["approved","finished","in-progress"])
            ->whereYear("requestdbs.start_date",$yearnow)
            ->join('vacations','requestdbs.requestable_id','vacations.id')
            ->where("user_id",'=',Auth::id())
            ->where('requestdbs.requestable_type','=',"App\\Models\\".ucwords("vacation"))
            ->where('type',$type)
            ->sum('count');
        return $takendays;
    }
    public function CheckVacationBalance($type,$duration){
  $vacationbalance=$this->VacationBalance($type);
$takendays=$this->takendays($type);
if($vacationbalance<=$takendays) return 'You have reached the maximum vacation days';
if($vacationbalance>$takendays){
    $days=$vacationbalance-$takendays;
if(($takendays+$duration)>$vacationbalance) return "Maximum {$type} Vacation days are {$vacationbalance} remaining days are {$days} you have requested {$duration} days ";
else{
    return true;
}

}

    }
    public function VacationDuration(Request $request){

        if(Carbon::parse(date('Y-m-d', strtotime($request['end_date'])))<Carbon::parse(date('Y-m-d', strtotime($request['start_date'])))) return $this->errorResponse('invalid data',400);
        $from_date = Carbon::parse(date('Y-m-d', strtotime($request['start_date'])));
        $through_date = Carbon::parse(date('Y-m-d', strtotime($request['end_date'])));
        $years = $from_date->diffInYears($through_date); // check vacation in the same year (start-end)
        $yearnow = Carbon::parse(date('Y-m-d', strtotime(Carbon::today())))->year; // Get year
        $requestyear =  $from_date->year;// check vacation in the same year(now - start)
        if($years>0 || $yearnow<$requestyear) return false;
        $duration = ($from_date->diffInDays($through_date))+1;
    return $duration;
    }
    public function CheckPendingRequests($type){
        $takendays= Requestdb::with(['requestable'])
            ->join('users', 'requestdbs.user_id', 'users.id')
            ->where('requestdbs.status', '=', "pending")
            ->where("user_id",'=',Auth::id())
            ->join('vacations','requestdbs.requestable_id','vacations.id')
            ->where('requestdbs.requestable_type','=',"App\\Models\\".ucwords("vacation"))
            ->where('type',$type)
            ->count();
        return $takendays;
    }
    public function ScheduledVacation(VacationRequest $request)
    {

        if($this->CheckPendingRequests($request['type'])>0) return $this->errorResponse("There is pending Vacation Requests",400);
        $duration=$this->VacationDuration($request);
        if(!$duration){
            return $this->errorResponse("Vacation can only be done during the same year",400);
        }
      $check=  $this->CheckVacationBalance($request['type'],$duration);

if (is_string($check)) return $this->errorResponse($check,400);
        if ( $request['type']=='scheduled')
            if( !$this->CheckRequestDate($request['start_date']))  return $this->errorResponse("Cant make request in a holiday or in a time in the past",400);
      else{
        $vacation = new Vacation();
        $vacation->type=$request['type'];
        $vacation->count=$duration;
        $vacation->save();
        $requestdb= $this->Create_Request($request);
        $vacation->requests()->save($requestdb);

    $response = ["message" => "Vacation Request Succesfully created", "Request" => $requestdb];
        return $this->showCustom($response, 201);
    }}
    public function UnscheduledVacation(VacationRequest $request,Absence $absence)
    {
error_log($absence);
        if($absence==null || $absence->user_id!=Auth::id()){
          return   $this->errorResponse("There is no absent on this day ",404);
        }
        $request['start_date']=$absence->date;
        $request['end_date']=$absence->date;
     return   $this->ScheduledVacation($request);

    }
    public function ShowVacationRequest(Vacation $vacation)
    {

        if( $vacation->requests->first()->user_id != Auth::id() || !Auth::user()->hasPermissionTo('Show_Vacation_Request'))
        {   return $this->errorResponse("The authenticated user is not permitted to perform the requested operation ",403);}

        return $this->showCustom($vacation,200);

    }
    public function RemainingVacationBalance(){
        $scheduled=$this->VacationBalance('scheduled')-$this->takendays('scheduled');
        $unscheduled=$this->VacationBalance('unscheduled')-$this->takendays('unscheduled');
        $response=["scheduled"=>$scheduled,"unscheduled"=>$unscheduled];
        return $this->showCustom($response,200);
    }
}
