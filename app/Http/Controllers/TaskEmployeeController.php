<?php

namespace App\Http\Controllers;

use App\Models\Task_employee;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskEmployeeController extends RequestController
{


    public function ShowAllAssignedTasks(Request $request){
        $status=$request->query('status');
        if($status==null){
            $Tasks=Task::join('task_employees','task_employees.task_id','tasks.id')
                ->join('requestdbs','requestdbs.requestable_id','tasks.id')
                ->where('requestdbs.requestable_type','App\\Models\\Task')
                ->where('task_employees.user_id','=',Auth::id())
              ->select('tasks.id as Task_id','task_employees.user_id as user_id','requestdbs.user_id as supervisor',
                   'requestdbs.start_date','requestdbs.end_date','tasks.description','requestdbs.status as status','task_employees.status as progress')
                ->get();
        }else{
            $Tasks=Task::join('task_employees','task_employees.task_id','tasks.id')
                ->join('requestdbs','requestdbs.requestable_id','tasks.id')
                ->where('requestdbs.requestable_type','App\\Models\\Task')
                ->where('task_employees.user_id','=',Auth::id())
                ->where('requestdbs.status','=',$status)
                ->select('tasks.id as Task_id','task_employees.user_id as user_id','requestdbs.user_id as supervisor',
                    'requestdbs.start_date','requestdbs.end_date','tasks.description','requestdbs.status as status','task_employees.status as progress')
                ->get();}
        return $this->showCustom( $Tasks,200);
    }



    public function AddEmployee(Task $task, Request $request)
    {
        if ($task === null) {
            return $this->errorResponse("Request is not found", 404);
        }
        $message='';
        if ($task->requests()->first()->user_id == Auth::id()) {
            foreach($request['employees'] as $item) {
                $check= User::where('id', '=',$item)->exists();
                if (!$check){
                    return $this->errorResponse("User{{$item}} is not found ", 404);

                }
                if(Auth::id()==$item){
                    return $this->errorResponse("Can't Assign Task to yourself ", 404);

                }
            }
            foreach ($request['employees'] as $item) {
                if(!$task->employees()->where('user_id',$item)->exists()){

                $task->employees()->create(['user_id' => $item]);}
                else  $message=$message.''.$item." ";
            }
            if($message=='')
            return $this->showCustom($task->employees()->get(),201);
            else{
              $response= ["message"=>$message."Already Exist","Employees"=>$task->employees()->get()];
                return $this->showCustom($response,201);}
        } else {
            return $this->errorResponse("You don't have the permission", 401);
        }
    }
    public function DeleteEmployee(Task $task, Request $request)
    { // add permissions
        if ($task === null) {
            return $this->errorResponse("Request is not found", 404);
        }
if($request['employees']==null){
    return $this->errorResponse("Please provide data", 404);

}
        if ($task->requests()->first()->user_id == Auth::id()) {
            foreach($request['employees'] as $item) {
                $check= User::where('id', '=',$item)->exists();
                if (!$check){
                    return $this->errorResponse("User{{$item}} is not found ", 404);

                }
                if (!$task->employees()->where('user_id',$item)->exists()){
                    return $this->errorResponse("User{{$item}} is not assigned to this task ", 404);

                }
            }
        if($task->employees()->first()===null)  return $this->showCustom("There is No Employee to delete",404);
            foreach ($request['employees'] as $item) {
                $task->employees()->where('user_id' , $item)->delete();
            }
           if( $task->employees()->first()===null){
               return $this->showCustom("There is no Employees assigned to this task ",200);

           }
            return $this->showCustom($task->employees()->get(),200);
        } else {
            return $this->errorResponse("You don't have the permission", 401);
        }
    }
    public function ShowEmployees(Task $task)
    {
        if ($task === null) {
            return $this->errorResponse("Request is not found", 404);
        }
        if ($task->requests()->first()->user_id == Auth::id() || $task->employees()->where('user_id',Auth::id())) {
            $response= ["Employees"=>$task->employees()->get()];
            return $this->showCustom($response,200);
            }
         else {
            return $this->errorResponse("You don't have the permission", 401);
        }
    }
    public function MarkTheTaskasCompleted(Task $task){
        if ($task === null) {
            return $this->errorResponse("Request is not found", 404);
        }
        $user=User::find($task->employees()->first()->user_id);

        if($task->employees()->where('user_id',Auth::id())||$user->supervisor==Auth::id()){
            $task->employees()->where('user_id',Auth::id())->update(["status"=>"completed"]);
           $task->requests()->update(['status'=>'finished']);
            return $this->showCustom($task->employees()->where('user_id',Auth::id())->get(),200);

        }
        else{
            return $this->errorResponse("You don't have the permission", 401);

        }

    } public function MarkTheTaskasseen(Task $task){
        if ($task === null) {
            return $this->errorResponse("Request is not found", 404);
        }
        $user=User::find($task->employees()->first()->user_id);

        if($task->employees()->where('user_id',Auth::id())||$user->supervisor==Auth::id()){
            $task->employees()->where('user_id',Auth::id())->update(["status"=>"seen"]);
            return $this->showCustom($task->employees()->where('user_id',Auth::id())->get(),200);

        }
        else{
            return $this->errorResponse("You don't have the permission", 401);

        }

    }
}
