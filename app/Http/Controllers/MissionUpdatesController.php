<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\MissionUpdateRequest;
use App\Models\Mission;
use App\Models\MissionUpdate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
//Show_Mission_Update_Request
class MissionUpdatesController extends ApiController
{
    public function store(MissionUpdateRequest  $request){
        $mission=Mission::find($request['mission_id']);
       
        if($mission===null){
            return $this->errorResponse("MissionUpdate not found",404);
        }
        if($mission->requests()->first()->status!=='approved'||strtotime($mission->requests()->first()->end_date)<strtotime($request['date'])||$mission->requests()->first()->user_id!=Auth::id()){
          // print_r($mission->requests()->first()->status!='approved');
            return $this->errorResponse("MissionUpdate in pending status or time of update vanished",400);
        }
        if($request->hasFile('approved_file')){
            $path=$request->file('approved_file')->store('public/missionUpdateImages');
            $missionUpdate=new MissionUpdate(['description'=>$request['description'],'date'=>$request['date'],'extra_cost'=>$request['extra_cost'],'approved_file'=>$path]);
            $mission->missionUpdates()->save($missionUpdate);
            return $this->showCustom("mission update saved", 200);
        }   
    }
    public function destroy($id){
        $missionUpdate=MissionUpdate::find($id);
        if($missionUpdate===null){
            return $this->errorResponse("MissionUpdate not found",404);
        }else{
             if(! $missionUpdate->mission->requests->first()->user_id == Auth::id() || ! Auth::user()->hasPermissionTo('Show_Mission_Update_Request'))
            return  $this->errorResponse("You do not have the permission",403);
      
            $missionUpdate->delete();
            return $this->showCustom('MissionUpdate deleted',200);    
        }
    }
    public function show($id){
        $missionUpdate=MissionUpdate::find($id);
        if($missionUpdate===null){
            return $this->errorResponse("MissionUpdate not found",404);
        }else{
            if(! $missionUpdate->mission->requests->first()->user_id == Auth::id() || ! Auth::user()->hasPermissionTo('Show_Mission_Update_Request'))
            return  $this->errorResponse("You do not have the permission",403);
      
            return $this->showOne($missionUpdate, 200);    
        }
    }

}