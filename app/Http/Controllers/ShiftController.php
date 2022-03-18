<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreShiftRequest;
use App\Http\Resources\ShiftCollection;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Http\Request;

class ShiftController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shifts = Shift::all();
        if ($shifts === null) {
            return $this->errorResponse("shifts not found", 404);
        } else {
            return $this->showAll($shifts, 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreShiftRequest $request)
    {
        $shift = Shift::create([
            'name' => $request['name'],
            'start_time' => $request['start_time'],
            'end_time' => $request['end_time']
        ]);
        $response = ["Shift" => $shift];
        return $this->showCustom($response, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $shift = Shift::where('id', $id)->first();
        if ($shift === null) {
            return $this->errorResponse("shift not found", 404);
        } else {
            return $this->showOne($shift, 200);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreShiftRequest $request, $id)
    {
        $shift = Shift::find($id);
        if ($shift === null) {
            return $this->errorResponse("shift not found", 404);
        } else {
            $shift->update([
                'name' => $request['name'],
                'start_time' => $request['start_time'],
                'end_time' => $request['end_time']
            ]);
            return $this->showOne($shift, 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $shift = Shift::find($id);
        if ($shift === null) {
            return $this->errorResponse("shift not found", 404);
        } else {
            $shift->delete();
            return $this->showCustom(['shift deleted'], 200);
        }
    }

    public function getUsersOfShift($id)
    {
        $usersOFShift=Shift::find($id);
        if ($usersOFShift === null) {
            return $this->errorResponse("users not found", 404);
        }else{
            $users=$usersOFShift->users()->paginate();
            return new ShiftCollection($users);
        }
        
    }
    public function getUserShiftById($id)
    {
        $user = User::find($id);
        if ($user === null) {
            return $this->errorResponse("User not found", 404);
        } else {
            $shiftUser = $user->shift()->first();
            return $this->showOne($shiftUser, 200);
        }
    }
    public function updateUserShift(Request $shift_id,$id){
        if($shift_id['id']===null){
            return $this->errorResponse("Bad Request", 400);
        }
        $user =User::find($id);
        if ($user === null) {
            return $this->errorResponse("User not found", 404);
        } else {
            $user->update(['shift_id'=>$shift_id['id']]); 
            return $this->showOne($user, 200);   
        }
    }
}
