<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\VacationdayRequest;
use App\Http\Resources\UserVacationdayCollection;
use App\Http\Requests\VacationdayUpdateRequest;
use App\Http\Resources\VacationdayResource;
use App\Models\Vacationday;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class VacationdayController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with(['vacationday'])->paginate();
        if($users===null){
            return $this->errorResponse("Users are not existed", 404);
        }else{
            return new UserVacationdayCollection($users);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VacationdayRequest $request)
    {
        $vacationday=Vacationday::create( ['user_id' => $request['user_id'],
        'scheduled' => $request['scheduled'],
        'unscheduled'=>$request['unscheduled']]);
        return $this->showCustom($vacationday, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user=User::find($id);
        if($user==null){
            return $this->errorResponse("user not found", 404);
        }else{
            return new VacationdayResource($user);
        }
    }
    public function showVacationdayAuth(){
        return $this->show(Auth::id());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(VacationdayUpdateRequest $request, $id)
    {
        $vacationday=Vacationday::find($id);
        if($vacationday==null){
            return $this->errorResponse("user not found", 404);
        }else{
            $vacationday->update([
                'scheduled' => $request['scheduled'],
                'unscheduled' => $request['unscheduled'],
                ]);
            $this->showCustom($vacationday,200);
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
        $vacationday=Vacationday::find($id);
        if($vacationday==null){
            return $this->errorResponse("user not found", 404);
        }else{
            $vacationday->delete();
            $this->showCustom("vacation day of that user deleted",200);
        }
    }
}
