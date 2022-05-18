<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class GetCompanyStstistics extends ApiController
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $departments=DB::table('departments')->count();
        $jobtitles=DB::table('job__titles')->count();
        $shifts=DB::table('shifts')->count();
        $users=DB::table('users')->count();
        return $this->showCustom(["departments"=>$departments,"jobtitles"=>$jobtitles,"shifts"=>$shifts,"users"=>$users]);
                
    }
}
