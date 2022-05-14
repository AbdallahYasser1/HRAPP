<?php

namespace App\Http\Controllers;

use App\Http\Resources\WFHCollection;
use App\Models\Requestdb;
use App\Models\Task;
use App\Models\User;
use App\Models\Wfh;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        if(count($request)==0) return $this->showCustom("There is no requests",200);
        return $this->showCustom($request,200);
    }
    public function ShowAllRequests(Request $request,$class){
        $status = $request->query('status');
        if ($status === null) {
            $requestes = Requestdb::with(['requestable'])
                ->join('users', 'requestdbs.user_id', 'users.id')

                ->where('requestdbs.requestable_type','=',$class)

                ->paginate()->appends(request()->query());
        } else {
            $requestes = Requestdb::with(['requestable'])
                ->join('users', 'requestdbs.user_id', 'users.id')
                ->where('requestdbs.status', '=', $status)

                ->where('requestdbs.requestable_type','=',$class)

                ->paginate()->appends(request()->query());
        }
        if ($requestes->count()==0) {
            return $this->errorResponse("wfh not found", 404);
        } else {
            return $this->showCustom( $requestes,200);
        }

    }
    public function ShowRequestsUserClass(Request $request,$class){
        $status = $request->query('status');
        if ($status === null) {
            $requestes = Requestdb::with(['requestable'])
                ->join('users', 'requestdbs.user_id', 'users.id')

                ->where('requestdbs.requestable_type','=',$class)
->where("user_id",'=',Auth::id())
           ->select('requestdbs.id as request_id','requestdbs.requestable_id as class_id','requestdbs.requestable_type as class','users.id as user_id', 'users.name','requestdbs.start_date','requestdbs.end_date' , 'requestdbs.status as request_status' )
                ->paginate()->appends(request()->query());
            error_log($requestes);
        } else {
            $requestes = Requestdb::with(['requestable'])
                ->join('users', 'requestdbs.user_id', 'users.id')
                ->where('requestdbs.status', '=', $status)
                ->where("user_id",'=',Auth::id())
                ->where('requestdbs.requestable_type','=',$class)
                ->select('requestdbs.id as request_id','requestdbs.requestable_id as class_id','requestdbs.requestable_type as class','users.id as user_id', 'users.name','requestdbs.start_date','requestdbs.end_date' , 'requestdbs.status as request_status' )

                ->paginate()->appends(request()->query());
        }
        if ($requestes->count()==0) {
            return $this->errorResponse("{$class} not found", 404);
        } else {
            return $this->showCustom( $requestes,200);
        }

    }
    public function ShowAllUserRequestsFilter(Request $request){
        $status = $request->query('status');
        $class=$request->query('class');
        if ($status === null && $class===null) {
            $requestes = Requestdb::with(['requestable'])
                ->join('users', 'requestdbs.user_id', 'users.id')

->where("user_id",'=',Auth::id())
           ->select('requestdbs.id as request_id','requestdbs.requestable_id','requestdbs.requestable_type','users.id as user_id', 'users.name','requestdbs.start_date','requestdbs.end_date' , 'requestdbs.status as request_status' )
                ->paginate()->appends(request()->query());

        } else {
            if($status != null && $class===null)
            $requestes = Requestdb::with(['requestable'])
                ->join('users', 'requestdbs.user_id', 'users.id')
                ->where('requestdbs.status', '=', $status)
                ->where("user_id",'=',Auth::id())
                ->select('requestdbs.id as request_id','requestdbs.requestable_id','requestdbs.requestable_type','users.id as user_id', 'users.name','requestdbs.start_date','requestdbs.end_date' , 'requestdbs.status as request_status' )

                ->paginate()->appends(request()->query());
        elseif ($status === null && $class!=null){
            $requestes = Requestdb::with(['requestable'])
                ->join('users', 'requestdbs.user_id', 'users.id')
                ->where('requestdbs.requestable_type','=',"App\\Models\\".ucwords($class))
                ->where("user_id",'=',Auth::id())
                ->select('requestdbs.id as request_id','requestdbs.requestable_id','requestdbs.requestable_type','users.id as user_id', 'users.name','requestdbs.start_date','requestdbs.end_date' , 'requestdbs.status as request_status' )
                ->paginate()->appends(request()->query());
        }
        else{
            $requestes = Requestdb::with(['requestable'])
                ->join('users', 'requestdbs.user_id', 'users.id')
                ->where('requestdbs.status', '=', $status)
                ->where("user_id",'=',Auth::id())
                ->where('requestdbs.requestable_type','=',"App\\Models\\".ucwords($class))
                ->select('requestdbs.id as request_id','requestdbs.requestable_id','requestdbs.requestable_type','users.id as user_id', 'users.name','requestdbs.start_date','requestdbs.end_date' , 'requestdbs.status as request_status' )
                ->paginate()->appends(request()->query());
        }
        }
        if ($requestes->count()==0) {
            return $this->errorResponse("There is no requests found", 404);
        } else {
            return $this->showCustom( $requestes,200);
        }

    }
    public function ShowAllRequestsAdmin(Request $request){
        $status = $request->query('status');
        $class=$request->query('class');
        if ($status === null && $class===null) {
            $requestes = Requestdb::with(['requestable'])
                ->join('users', 'requestdbs.user_id', 'users.id')
           ->select('requestdbs.id as request_id','requestdbs.requestable_id as class_id','requestdbs.requestable_type as class','users.id as user_id', 'users.name','requestdbs.start_date','requestdbs.end_date' , 'requestdbs.status as request_status' )
                ->paginate()->appends(request()->query());

        } else {
            if($status != null && $class===null)
            $requestes = Requestdb::with(['requestable'])
                ->join('users', 'requestdbs.user_id', 'users.id')
                ->where('requestdbs.status', '=', $status)
                ->select('requestdbs.id as request_id','requestdbs.requestable_id as class_id','requestdbs.requestable_type as class','users.id as user_id', 'users.name','requestdbs.start_date','requestdbs.end_date' , 'requestdbs.status as request_status' )

                ->paginate()->appends(request()->query());
        elseif ($status === null && $class!=null){
            $requestes = Requestdb::with(['requestable'])
                ->join('users', 'requestdbs.user_id', 'users.id')
                ->where('requestdbs.requestable_type','=',"App\\Models\\".ucwords($class))
                ->select('requestdbs.id as request_id','requestdbs.requestable_id as class_id','requestdbs.requestable_type as class','users.id as user_id', 'users.name','requestdbs.start_date','requestdbs.end_date' , 'requestdbs.status as request_status' )
                ->paginate()->appends(request()->query());
        }
        else{
            $requestes = Requestdb::with(['requestable'])
                ->join('users', 'requestdbs.user_id', 'users.id')
                ->where('requestdbs.status', '=', $status)
                ->where('requestdbs.requestable_type','=',"App\\Models\\".ucwords($class))
                ->select('requestdbs.id as request_id','requestdbs.requestable_id as class_id','requestdbs.requestable_type as class','users.id as user_id', 'users.name','requestdbs.start_date','requestdbs.end_date' , 'requestdbs.status as request_status' )
                ->paginate()->appends(request()->query());
        }
        }
        if ($requestes->count()==0) {
            return $this->errorResponse("There is no requests found", 404);
        } else {
            return $this->showCustom( $requestes,200);
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
    public function UpdateRequest(Requestdb $model,Request $request)
    {
        if ($model === null){
            return $this->errorResponse("Request is not found", 404);}
        else{
            if ($model->requests()->first()->user_id == Auth::id() && $model->requests()->first()->status="pending" )
            {
               if ($request['start_date']!=null) $this->CheckRequestDate($request['start_date']);
                if ($request['end_date']!=null)  $this->CheckRequestDate($request['end_date']);
                $model->requests()->update($request->all());

                return $this->showCustom($model->requests->first(),200);
            } else {
                return $this->errorResponse("You don't have the permision to update", 401);
            }
        }

    }
public function CheckRequestDate($date){
    $timeOfDate = date('Y-m-d', time());
    $timeOfTask = date('Y-m-d', strtotime($date));
    $now = date('H', time());
    $shift = User::find(Auth::id())->shift()->first();
    //$timeShift = date('H', strtotime($shift->start_time));
    if ($timeOfTask < $timeOfDate)
        return $this->errorResponse("Can not make a request in a past date", 400);
    $holiday = DB::table('holidays')->where('date', '=', $date)->exists();
    if($holiday)
    return $this->errorResponse("Can not make a request in a holiday", 400);
    //if ($timeOfTask == $timeOfDate && !($timeShift - $now > 0)) {
      //  return $this->errorResponse("Can not make a request in a past date", 400);
    return true;
    //}
}
}

