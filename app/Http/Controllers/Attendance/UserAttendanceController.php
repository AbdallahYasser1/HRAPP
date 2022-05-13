<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use DateTime;
use Illuminate\Support\Facades\Auth;
use App\Traits\CheckLocation;

class UserAttendanceController extends ApiController
{
    use CheckLocation;

    public function attendEmployee(Request $request, $id)
    {
        // if holiday or fixed weekend
        // Check Shift start time -> Attendance
        // error time passed
        // Scheduler shift + 15 min ->
        //\ absent table
        //
        $rules = [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'status' => 'required',
        ];
        $this->validate($request, $rules);

        $premise = $this->checkDistance($request->latitude, $request->longitude);
        $isOnPremies = $premise['onPremises'];

        if ($isOnPremies) {
        if ($request['status'] == 'start')
           return  $this->store($request, $id);
        else
            return $this->update($request, $id);
    } else
        return $this->errorResponse('You are not on premies, you are away by '. $premise['distance']. ' meters', 401);

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
    public function store(Request $request, $id)
    {

        $user = User::findOrFail($id);
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
    public function update(Request $request, $id)
    {
        // $user = Auth::user();
        $user = User::findOrFail($id);
        $attend = $user->attendances->last();
        // $attend->leave_time = new DateTime();
        // $attend->save();
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
}
