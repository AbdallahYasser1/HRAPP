<?php

namespace App\Http\Controllers\Attendance;
date_default_timezone_set ("Africa/Cairo");

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\User;
use DateTime;
use Illuminate\Support\Facades\Auth;
use App\Traits\CheckLocation;
class UserAttendanceController extends ApiController
{
    use CheckLocation;

    public function attendEmployee(Request $request, $id)
    {
        // absent table
        $rules = [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'status' => 'required',
        ];
        $this->validate($request, $rules);

        $user = User::find($id);

        $premise = $this->checkDistance($request->latitude, $request->longitude);
        $isOnPremies = $premise['onPremises'];


        $Holiday = Holiday::where('date', '=', date('Y-m-d'))->get()->first()->date;
        $isTodayHoliday = date("Y-m-d") == $Holiday;

        $isWeekend = date('N') >= 6;

        $isOnTime = $this->checkTime($user);

        $isLate = $this->checkLate($user);

        $outerConditions = $isOnPremies && !$isTodayHoliday && $isOnTime && !$isLate && !$isWeekend;

        if ($outerConditions) {

        if ($request['status'] == 'start')
           return  $this->store($request, $user);
        else
            return $this->update($request, $user);
    } else {
        $error = 'You are not allowed to work today';
        return $this->errorResponse($error, 401);
    }
}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $user = User::findOrFail($id);
        $attend = $user->attendances;
        return $this->showAll($attend);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $user)
    {

        $data = $request->all();

        $data['user_id'] = $user->id;
        $data['start_time'] = new DateTime();
        $data['date'] = date('Y-m-d');
        $data['leave_time'] = null;

        $attend = Attendance::create($data);
        return $this->showOne($attend);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
        $attend->update([
            'leave_time' => new DateTime(),
        ]);
        return $this->showOne($attend);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
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
