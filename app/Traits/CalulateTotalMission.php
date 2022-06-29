<?php
namespace App\Traits;
use App\Models\Mission;
use App\Models\Salary\SalaryAdjustment;
use App\Models\Salary\SalaryAdjustmentType;
use App\Models\User;
use GuzzleHttp\Psr7\Request;

trait CalulateTotalMission
{
    public function calculateTotalMissionAndMissionUpdates(Mission $mission){
        $combineResult=[];
        $combineResult['mission_id']=$mission->id;
        $combineResult['mission_description']=$mission->description;
        $combineResult['mission_paid']=$mission->paid;
        $combineResult['user_id']=$mission->requests->first()->user_id;
        $combineResult['request_id']=$mission->requests->first()->id;
        $combineResult['initial_cost']=$mission->initial_cost;
        $combineResult['extra_cost']=$mission->missionUpdates->sum('extra_cost');
        $combineResult['total']=$combineResult['extra_cost']+$combineResult['initial_cost'];
        return $combineResult;
    }


    public function addAmountToSlip($data) {
        $user_id = $data['user_id'];
        $amount = $data['total'];
        $name = $data['mission_description'];

        $user = User::find($user_id);
        $slip = $user->lastSlip;

        $adjustmentType = new SalaryAdjustmentType();
        $adjustmentType->name = $name;
        $adjustmentType->save();
        $slip->adjustments()->create([
            'salary_adjustment_type_id' => $adjustmentType->id,
            'salary_slip_id' => $slip->id,
            'amount' => $amount,
            'date' => date('Y-m-d'),
            'title'=>$adjustmentType->name,
        ]);
    }



}



