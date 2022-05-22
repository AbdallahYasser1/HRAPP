<?php

namespace App\Http\Controllers;

use App\Models\Requestdb;
use App\Models\Task;
use App\Models\Task_employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class TaskController extends RequestController
{
    public function store(Request $request)
    {
        $timeOfDate = date('Y-m-d', time());
        $timeOfTask = date('Y-m-d', strtotime($request['start_date']));
        $endtimeOfTask = date('Y-m-d', strtotime($request['end_date']));
        if ($timeOfTask < $timeOfDate|| $endtimeOfTask<$timeOfDate)
            return $this->errorResponse("Cant making task in past date", 400);
        foreach($request['employees'] as $item) {
           $check= User::where('id', '=',$item)->exists();
           if (!$check){
               return $this->errorResponse("User{{$item}} is not found ", 404);

           }
           if(Auth::id()==$item){
               return $this->errorResponse("Can't Assign Task to yourself ", 404);

           }
        }

            $task = new Task;
        $task->description=$request['description'];
        $task->save();
        $requestdb=$this->Create_Request($request);
        if($timeOfDate==$timeOfTask)
        $requestdb->status='in-progress';
        else
        $requestdb->status='approved';
        $task->requests()->save($requestdb);
        foreach($request['employees'] as $item) {
            if(!$task->employees()->where('user_id',$item)->exists()){
                $task->employees()->create(['user_id'=>$item]);}
        }

        $response = ["message" => "Task Request Has Succesfully created", "Request" => $requestdb,"Task"=>$task];
        return $this->showCustom($response, 201);
    }

    public function ShowTask(Task $task)
    {
        if ($task === null)
            return $this->errorResponse("Task not found", 404);
        if($task->id!=Auth::id() || ! Auth::user()->hasPermissionTo('Show_Task_Request'||!$task->employees()->where('user_id',Auth::id()))){
            $this->errorResponse("You do not have the permission",403);
        }
        return $this->showOne($task,200);
        }
  public function ShowAllTasks()
    {
    return $this->ShowAllUserRequests(Task::class);
        }

    public function update(Request $request, Task $task)
    {
        if ($task === null) {
            return $this->errorResponse("Task is not found", 404);
        } else {
            if ($task->requests->first()->user_id == Auth::id()) {
                $task->update($request->all());
                return $this->showCustom($task->requests->first(),200);
            } else {
                return $this->errorResponse("You don't have the permision to update", 401);
            }
        }
    }
    public function CancelTask(Task $task)
    {
 return $this->CancelRequest($task);
    }
}
