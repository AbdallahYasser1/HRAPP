<?php

namespace App\Http\Controllers;

use App\Http\Resources\WFHCollection;
use App\Models\Requestdb;
use App\Models\Task;
use App\Models\Wfh;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestController extends ApiController
{
    public function Create_Request(Request $request){
        $requestdb = new Requestdb;
        $requestdb->user_id = Auth::id();
        $requestdb->start_date = $request['start_date'];
        $requestdb->end_date = $request['end_date'];
        return $requestdb;
    }
    public function ShowAllUserRequests($class)
    {
        $request= Requestdb::whereHasMorph(
            'requestable',
            [$class],
            function (Builder $query) {
                $query->where('user_id', '=', Auth::id());
            }
        )->get();


        return $this->showCustom($request,200);
    }
    public function ShowAllRequests(Request $request,$class){
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
    public function CancelRequest(Model $model)
    {
        if ($model === null){
            return $this->errorResponse("Request is not found", 404);}
        else{
            if ($model->requests()->first()->user_id == Auth::id()) {
                $model->requests()->update(['status'=>'canceled']);

                return $this->showCustom($model->requests->first(),200);
            } else {
                return $this->errorResponse("You don't have the permision to update", 401);
            }
        }

    }
    public function UpdateRequest(Model $model,Request $request) // missing request validation
    {
        if ($model === null){
            return $this->errorResponse("Request is not found", 404);}
        else{
            if ($model->requests()->first()->user_id == Auth::id()) {
                $model->requests()->update($request->all());

                return $this->showCustom($model->requests->first(),200);
            } else {
                return $this->errorResponse("You don't have the permision to update", 401);
            }
        }

    }

}

