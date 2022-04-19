<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\MissionRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\Requestdb;
use App\Models\Mission;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class MissionController extends ApiController
{
    public function storeDB($request)
    {
        $mission = new Mission;
        $mission->description=$request['description'];
        $mission->initial_cost=$request['initial_cost'];
        $mission->save();
        $requestdb = new Requestdb;
        $requestdb->user_id = Auth::id();
        $requestdb->start_date =$request['start_date'];
        $requestdb->end_date = $request['end_date'];
        $mission->requests()->save($requestdb);

        $response = ["message" => "Mission Request Succesfully created", "Request" => $requestdb];
        return $this->showCustom($response, 201);
    }
    public function store(MissionRequest $request)
    {
        $requestdb = DB::table('requestdbs')->where('start_date', '=', $request['start_date'])->where('requestable_type','=','App\\Models\\Mission')->where('user_id', Auth::id())->exists();
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
                return $this->errorResponse("cant making mission in past date", 400);
            }
        } else {
            return $this->errorResponse("this time is holiday or request is in under status", 400);
        }
    }
    public function update(Request $request, $id)
    {
        $mission = Mission::find($id);
        if ($mission === null) {
            return $this->errorResponse("mission not found", 404);
        } else {
            if ($mission->requests->first()->user_id == Auth::id()) {
                $mission->requests->first()->status = $request['status'];
                // can mark it as accepted should be fixed
                return $this->showCustom($mission->requests->first(),200);
            } else {
                return $this->errorResponse("user update anthor user mission", 401);
            }
        }
    }
    public function destroy($id)
    {
        $mission = Mission::find($id);
        if ($mission === null) {
            return $this->errorResponse("mission not found", 404);
        } else {
            if ($mission->requests->first()->user_id == Auth::id()) {
                $mission->requests->first()->status = 'canceled';
                return $this->showCustom($mission->requests->first(),200);
            } else {
                return $this->errorResponse("user delete anthor user mission", 401);
            }
        }
    }
    public function showMissionRequest(Mission $mission)
{
    if(! $mission->requests->first()->user_id == Auth::id() || ! Auth::user()->hasPermissionTo('Show_mission_Request'))
         $this->errorResponse("You do not have the permission",403);
    return $this->showCustom(['mission'=>$mission,'updates'=>$mission->missionUpdates],200);

}
public function showAllMissionRequests()
{
    $request= Requestdb::whereHasMorph(
        'requestable',
        [Mission::class],
        function (Builder $query) {
            $query->where('user_id', '=', Auth::id());
        }
    )->get();


return $this->showCustom($request,200);
}
}
