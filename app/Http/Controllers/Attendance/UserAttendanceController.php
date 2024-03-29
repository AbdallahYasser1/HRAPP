<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserAttendanceController extends ApiController
{
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user= Auth::user();

        // $user = User::findOrFail($id);
        $attends = $user->attendances;
        return $this->showAll($attends);
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $attend = Attendance::findOrFail($id);
        return $this->showOne($attend);                 
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($user_id, $attend_id)
    {
        // $user = User::findOrFail($user_id);
        // $attend = $user->attendances()->findOrFail($attend_id);
        // $attend->delete();
        // return $this->showOne($attend);
    }

    public function getUserAttendanceByDate(Request $request)
    {
        $rules = [
            'date' => 'required|date|date_format:Y-m-d',
        ];
        $this->validate($request, $rules);
        $user = Auth::user();
        $attend = $user->attendances()->where('date', $request->date)->get()->first();
        return $this->showOne($attend);
    }

    public function getUserAttendanceByMonth(Request $request)
    {
        $rules = [
            'date' => 'required|date_format:Y-m',
        ];
        $this->validate($request, $rules);
        $user = Auth::user();
        $attends = $user->attendances()->where('date', 'like', $request->date.'%')->get();
        return $this->showAll($attends);
    }

}
