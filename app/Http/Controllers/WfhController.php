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

class WfhController extends ApiController
{
    public function storeDB($startDate)
    {
        $wfh = new Wfh;
        $wfh->save();
        $requestdb = new Requestdb;
        $requestdb->user_id = Auth::id();
        $requestdb->start_date = $startDate;
        $requestdb->end_date = $startDate;
        $wfh->requests()->save($requestdb);

        $response = ["message" => "WFH Request Succesfully created", "Request" => $requestdb];
        return $this->showCustom($response, 201);
    }
    public function store(Request $request)
    {
        $requestdb = DB::table('requestdbs')->where('start_date', '=', $request['start_date'])->where('user_id', Auth::id())->exists();
        $holiday = DB::table('holidays')->where('date', '=', $request['start_date'])->exists();
        $shift = User::find(Auth::id())->shift()->first();
        if (!$requestdb && !$holiday) {
            $timeOfDate = date('Y-m-d', time());
            $timeOfWFH = date('Y-m-d', strtotime($request['start_date']));
            $now = date('H', time());
            $timeShift = date('H', strtotime($shift->start_time));
            if ($timeOfWFH > $timeOfDate) {
                return $this->storeDB($timeOfWFH);
            } else if ($timeOfWFH == $timeOfDate && ($timeShift - $now > 0)) {
                return $this->storeDB($timeOfWFH);
            } else {
                return $this->errorResponse("cant making wfh in past date", 400);
            }
        } else {
            return $this->errorResponse("this time is holiday or request is in under status", 400);
        }
    }
    public function update(Request $request, $id)
    {
        $wfh = Wfh::find($id);
        if ($wfh === null) {
            return $this->errorResponse("Wfh not found", 404);
        } else {
            if ($wfh->requests->first()->user_id == Auth::id()) {
                $wfh->requests->first()->status = $request['status'];
                return $this->showCustom($wfh->requests->first(),200);
            } else {
                return $this->errorResponse("user update anthor user wfh", 401);
            }
        }
    }
    public function destroy($id)
    {
        $wfh = Wfh::find($id);
        if ($wfh === null) {
            return $this->errorResponse("Wfh not found", 404);
        } else {
            if ($wfh->requests->first()->user_id == Auth::id()) {
                $wfh->requests->first()->status = 'canceled';
                return $this->showCustom($wfh->requests->first(),200);
            } else {
                return $this->errorResponse("user delete anthor user wfh", 401);
            }
        }
    }
public function showWfhRequest(Wfh $wfh)
{
    if(! $wfh->requests->first()->user_id == Auth::id() || ! Auth::user()->hasPermissionTo('Show_Wfh_Request'))
         $this->errorResponse("You do not have the permission",403);
    return $this->showCustom($wfh,200);

}
public function showAllWfhRequests()
{
    $request= Requestdb::whereHasMorph(
        'requestable',
        [Wfh::class],
        function (Builder $query) {
            $query->where('user_id', '=', Auth::id());
        }
    )->get();


return $this->showCustom($request,200);
}
}
