<?php

namespace App\Http\Controllers;

use App\Models\Requestdb;
use App\Models\Vacation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VacationController extends Controller
{
    public function store(Request $request)
    {
        // Check Vacation balance
        $vacation = new Vacation();
        $vacation->type=$request['type'];
        // if vacation was unscheduled then it should be at time in past
        // so it removes any salary deducation
        $vacation->save();
        $requestdb = new Requestdb;
        $requestdb->user_id = Auth::id();
        $requestdb->start_date = $request['start_date'];
        $requestdb->end_date = $request['end_date'];
        $vacation->requests()->save($requestdb);
        $response = ["message" => "Vacation Request Succesfully created", "Request" => $requestdb];
        error_log($vacation);
        return $this->successResponse($response, 201);
    }
}
