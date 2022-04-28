<?php

namespace App\Http\Controllers;

use App\Models\Task_employee;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskEmployeeController extends RequestController
{
    public function AddEmployee(Task $task, Request $request)
    {
        if ($task === null) {
            return $this->errorResponse("Request is not found", 404);
        }
        $message='';
        if ($task->requests()->first()->user_id == Auth::id()) {

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
    {
        if ($task === null) {
            return $this->errorResponse("Request is not found", 404);
        }

        if ($task->requests()->first()->user_id == Auth::id()) {
        if($task->employees()->first()===null)  return $this->showCustom("There is No Employee to delete",404);
            foreach ($request['employees'] as $item) {
                $task->employees()->where('user_id' , $item)->delete();
            }
           if( $task->employees()->first()===null){
               return $this->showCustom("There is no Employees assigned to this task ",200);

           }
            return $this->showCustom($task->employees()->first(),200);
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
    public function MarkTheTask(Task $task,Request $request){
        if ($task === null) {
            return $this->errorResponse("Request is not found", 404);
        }
        if($task->employees()->where('user_id',Auth::id())){
            $task->employees()->where('user_id',Auth::id())->update(["status"=>$request['status']]);
            return $this->showCustom($task->employees()->where('user_id',Auth::id())->get(),200);

        }
        else{
            return $this->errorResponse("You don't have the permission", 401);

        }

    }
}
