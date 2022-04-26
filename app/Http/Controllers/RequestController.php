<?php

namespace App\Http\Controllers;

use App\Models\Requestdb;
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
}
