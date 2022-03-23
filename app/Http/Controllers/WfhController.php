<?php

namespace App\Http\Controllers;

use App\Models\Requestdb;
use App\Models\Wfh;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WfhController extends ApiController
{
    public function store(Request $request)
    {
        $wfh = new Wfh;
        $wfh->save();
        $requestdb = new Requestdb;
        $requestdb->user_id = Auth::id();
        $requestdb->start_date = $request['start_date'];
        $requestdb->end_date = $request['start_date'];
        $wfh->requests()->save($requestdb);
        $response = ["message" => "WFH Request Succesfully created", "Request" => $requestdb];
        error_log($wfh);
        return $this->successResponse($response, 201);
    }
    public function update(Request $request,$id){
        $wfh=Wfh::find($id);
        if($wfh===null){
            return $this->errorResponse("Wfh not found",404);
        }else{
            if($wfh->requests()->user()->id==Auth::id()){
                $wfh->requests();
            }else{
                return $this->errorResponse("user update anthor user wfh",401);
            }
        }
    }
}
