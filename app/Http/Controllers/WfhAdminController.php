<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wfh;
use App\Models\Requestdb;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Resources\WFHCollection;
class WfhAdminController extends ApiController
{
    public function update(Request $request, $id)
    {
        $wfh = Wfh::find($id);
        if ($wfh === null) {
            return $this->errorResponse("Wfh not found", 404);
        } else {
            $wfh->requests->first()->status = 'canceled';
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
            return $this->showCustom($wfh->requests->first(),200);
        }
    }
    public function showAllWFHRequestes(Request $request){
        $status = $request->query('status');
        if ($status === null) {
            $requestes = Requestdb::with(['requestable'])
                ->join('users', 'requestdbs.user_id', 'users.id')
                ->paginate()->appends(request()->query());
        } else {
            $requestes = Requestdb::with(['requestable'])
                ->join('users', 'requestdbs.user_id', 'users.id')
                ->where('requestdbs.status', '=', $status)
                ->paginate()->appends(request()->query());
        }
        if ($requestes->count()==0) {
            return $this->errorResponse("wfh not found", 404);
        } else {
            return new WFHCollection($requestes);
        }
        
    }
}
