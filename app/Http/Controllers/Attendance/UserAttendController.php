<?php

namespace App\Http\Controllers\Attendance;

date_default_timezone_set("Africa/Cairo");

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\User;
use DateTime;
use Illuminate\Support\Facades\Auth;
use App\Traits\CheckLocation;

class UserAttendController extends ApiController
{
    use CheckLocation;

    public function attendEmployee(Request $request, )
    {
        // absent table
        $rules = [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'status' => 'required',
        ];
        $this->validate($request, $rules);

        // $user = User::find($id);
        $user= Auth::user();


        $premise = $this->checkDistance($request->latitude, $request->longitude);
        $isOnPremies = $premise['onPremises'];

        $isTodayHoliday = Holiday::where('date', '=', date('Y-m-d'))->get()->first();

        $isWeekend = date('N') == 5 || date('N') == 6;

        $isOnTime = $this->checkTime($user);

        $isLate = $this->checkLate($user);
        // $isLate=false;

        $isUserOnVacation = false;

        $outerConditions = $isOnPremies && !$isTodayHoliday && $isOnTime && !$isWeekend && !$isUserOnVacation;
        // $outerConditions = true;

        if ($outerConditions) {

            if ($request['status'] == 'start') {
                if (!$isLate)
                    return $this->update($request, $user);
                else
                    return $this->errorResponse('You are late', 422);
            } elseif ($request['status'] == 'out') {
                return $this->update($request, $user);
            }
        } else {
            $error = 'You are not allowed to work today';
            return $this->errorResponse($error, 401);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $user)
    {
        $attend = $user->attendances->last();
        if ($request['status'] == 'start') {
            $attend->update([
                'start_time' => new DateTime(),
            ]);
        } elseif ($request['status'] == 'out') {
            $attend->update([
                'leave_time' => new DateTime(),
            ]);
        }
        return $this->showOne($attend);
    }


    public function checkTime($user)
    {
        $time = date('H:i:s');
        $startTime = $user->shift->start_time;
        $endTime = $user->shift->end_time;
        if ($time >= $startTime && $time <= $endTime) {
            return true;
        } else {
            return false;
        }
    }

    public function checkLate($user)
    {
        $time = date('H:i:s');
        $startTime = $user->shift->start_time;
        $endTime = date('H:i:s', strtotime("+15 minutes", strtotime($startTime)));
        if ($time > $endTime) {
            return true;
        } else {
            return false;
        }
    }
}
