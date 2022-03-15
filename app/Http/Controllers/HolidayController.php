<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreHoliday;
use App\Models\Holiday;
use App\Http\Controllers\ApiController;
use Illuminate\Database\Eloquent\Collection;

class HolidayController extends ApiController
{
    public function store(StoreHoliday $reqest){
        $holiday=Holiday::create([
            'name'=>$reqest['name'],
            'date'=>$reqest['date']
        ]);
        $response=["holiday"=>$holiday];
        return $this->showCustom($response,201);
    }
    public function show($id){
        $holiday=Holiday::where('id',$id)->first();
        if($holiday===null){
            return $this->errorResponse("Holiday not found",404);
        }else{
            return $this->showOne($holiday,200);
        }
    }
    public function update($id,StoreHoliday $reqest){
        $holiday=Holiday::find($id);
        if($holiday===null){
            return $this->errorResponse("Holiday not found",404);
        }else{
            $holiday->update([
                'name' => $reqest['name'],
                'date'=>$reqest['date']
            ]);
            return $this->showOne($holiday,200);
        }
    }
    public function destroy($id){
        $holiday=Holiday::find($id);
        if($holiday===null){
            return $this->errorResponse("Holiday not found",404);
        }else{
            $holiday->delete();
            return $this->showCustom(['holiday deleted'],200);    
        }
        }
    public function getAllHolidaysOfMonth($month){
        $holidays=Holiday::whereMonth('date',$month)->get();
        if($holidays->count()==0){
            return $this->errorResponse("Holiday not found",404);
        }else{
            return $this->showAll( $holidays,200);
        }

    }

}
