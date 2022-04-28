<?php

namespace App\Http\Controllers;

use App\Models\Requestdb;
use App\Models\Task;
use App\Models\Task_employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class TaskController extends RequestController
{
    public function store(Request $request)
    {
        $timeOfDate = date('Y-m-d', time());
        $timeOfTask = date('Y-m-d', strtotime($request['start_date']));
        if ($timeOfTask < $timeOfDate)
            return $this->errorResponse("Cant making task in past date", 400);
        $task = new Task;
        $task->description=$request['description'];
        $task->save();
        $requestdb=$this->Create_Request($request);
        $task->requests()->save($requestdb);
        foreach($request['employees'] as $item) {
            $task->employees()->create(['user_id'=>$item]);
        }

        $response = ["message" => "Task Request Has Succesfully created", "Request" => $requestdb,"Task"=>$task];
        return $this->showCustom($response, 201);
    }

    public function ShowTask(Task $task)
    {
        if ($task === null)
            return $this->errorResponse("Task not found", 404);
        if($task->id!=Auth::id() || ! Auth::user()->hasPermissionTo('Show_Task_Request')){
            $this->errorResponse("You do not have the permission",403);
        }
        return $this->showOne($task,200);
        }
  public function ShowAllTasks()
    {
    return $this->ShowAllUserRequests(Task::class);
        }


    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function CancelTask(Task $task)
    {
//        if ($task === null)
//            return $this->errorResponse("Task is not found", 404);
//        else
//            if ($task->requests->first()->user_id == Auth::id()) {
//                $task->requests()->status='canceled';
//                $task->requests()->save();
//                return $this->showCustom($task->requests->first(),200);
//            } else {
//                return $this->errorResponse("You don't have the permision to update", 401);
//            }
 return $this->CancelRequest($task);

    }
}
