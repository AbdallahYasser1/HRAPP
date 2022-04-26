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

    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        //
    }
}
