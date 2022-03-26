<?php

namespace App\Http\Controllers;

use App\Models\Requestdb;
use App\Models\Wfh;
use App\Models\User;
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
                $this->storeDB($timeOfWFH);
            } else if ($timeOfWFH == $timeOfDate && ($timeShift - $now > 0)) {
                $this->storeDB($timeOfWFH);
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
            if ($wfh->requests->user_id == Auth::id()) {
                $wfh->requests->status = $request['status'];
                return print_r($wfh->requests);
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
            if ($wfh->requests->user_id == Auth::id()) {
                $wfh->requests->status = 'canceled';
                return print_r($wfh->requests);
            } else {
                return $this->errorResponse("user delete anthor user wfh", 401);
            }
        }
    }
    public function adminUpdate(Request $request, $id)
    {
        $wfh = Wfh::find($id);
        if ($wfh === null) {
            return $this->errorResponse("Wfh not found", 404);
        } else {
            $wfh->requests->status = 'canceled';
            return print_r($wfh->requests);
        }
    }
    public function destroyAdmin($id)
    {
        $wfh = Wfh::find($id);
        if ($wfh === null) {
            return $this->errorResponse("Wfh not found", 404);
        } else {
            $wfh->delete();
            return print_r(1);
        }
    }
}
