<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        $task = new Task;
        $task->description=$request['description'];
        $task->save();
        $requestdb = new Requestdb;
        $requestdb->user_id = Auth::id();
        $requestdb->start_date = $request['start_date'];
        $requestdb->end_date = $request['end_date'];
        $task->requests()->save($requestdb);

        $response = ["message" => "Task Request Has Succesfully created", "Request" => $requestdb];
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
