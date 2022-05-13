<?php

namespace App\Http\Controllers;

use App\Models\Requestdb;
use App\Models\Wfh;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\WfhResource;
use App\Http\Resources\WFHRequestResource;

class WfhController extends RequestController
{
    public function storeDB(Request $request)
    {
        $wfh = new Wfh;
        $wfh->save();
        $requestdb=$this->Create_Request($request);
        $requestdb->end_date = $request['start_date'];
        $wfh->requests()->save($requestdb);
        $response = ["message" => "WFH Request Succesfully created", "Request" => new WFHRequestResource($requestdb)];
        return $this->showCustom($response, 201);
    }
    public function store(Request $request)
    {
        $requestdb = DB::table('requestdbs')->where('start_date', '=', $request['start_date'])->where('requestable_type','=','App\\Models\\Wfh')->where('user_id', Auth::id())->exists();
        $holiday = DB::table('holidays')->where('date', '=', $request['start_date'])->exists();
        $shift = User::find(Auth::id())->shift()->first();
        if (!$requestdb && !$holiday) {
            $timeOfDate = date('Y-m-d', time());
            $timeOfWFH = date('Y-m-d', strtotime($request['start_date']));
            $now = date('H', time());
            $timeShift = date('H', strtotime($shift->start_time));
            if ($timeOfWFH > $timeOfDate) {
                return $this->storeDB($request);
            } else if ($timeOfWFH == $timeOfDate && ($timeShift - $now > 0)) {
                return $this->storeDB($request);
            } else {
                return $this->errorResponse("Date must not be in the past", 400);
            }
        } else {
            return $this->errorResponse("Either Request under review or this day is a holiday ", 400);
        }
    }
    //question on update method 1111
    public function update(Request $request, $id)
    {
        $wfh = Wfh::find($id);
        if ($wfh === null) {
            return $this->errorResponse("Wfh not found", 404);
        } else {
            if ($wfh->requests->first()->user_id == Auth::id() &&  $wfh->requests->first()->status=='pending') {

                $wfh->requests->first()->start_date = $request['date'];
                $wfh->requests->first()->end_date = $request['date'];
                $wfh->requests->first()->save();

                return $this->showCustom($wfh->requests->first(),200);
            } else {
                return $this->errorResponse(" The authenticated user is not permitted to perform the requested operation", 401);
            }
        }
    }
    public function destroy(Wfh $wfh)
    {
     return $this->CancelRequest($wfh);
    }
public function showWfhRequest(Wfh $wfh)
{

    if( $wfh->requests->first()->user_id != Auth::id() || !Auth::user()->hasPermissionTo('Show_Wfh_Request'))
    {   return $this->errorResponse("The authenticated user is not permitted to perform the requested operation ",403);}

    return $this->showCustom(new WfhResource($wfh),200);

}
public function showAllWfhRequests()
{
return $this->ShowAllUserRequests(Wfh::class);
}
}
