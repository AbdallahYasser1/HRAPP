<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wfh;
use App\Models\Requestdb;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Resources\WFHCollection;
use Illuminate\Support\Facades\Auth;

class WfhAdminController extends RequestController
{
    public function update(Request $request, $id)
    {
        $wfh = Wfh::find($id);
        if ($wfh === null) {
            return $this->errorResponse("Wfh not found", 404);
        } else {
            $wfh->requests->first()->status = $request['status'];
            $wfh->requests->first()->bywhom=Auth::id();
            $wfh->requests->first()->save();
              
            return $this->showCustom($wfh->requests->first(),200);
        }
    }
    public function destroy($id)
    {
        $wfh = Wfh::find($id);
        if ($wfh === null) {
            return $this->errorResponse("Wfh not found", 404);
        } else {
            $wfh->requests->first()->status = 'canceled';
            $wfh->requests->first()->bywhom=Auth::id();
            $wfh->requests->first()->save();
              
            return $this->showCustom($wfh->requests->first(),200);
        }
    }
    public function showAllWFHRequestes(Request $request){
        return $this->ShowAllRequests($request,'App\\Models\\Wfh');
        
    }
}
