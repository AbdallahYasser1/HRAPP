<?php
namespace App\Traits;
use App\Models\Mission;
trait CalulateTotalMission
{
    public function calculateTotalMissionAndMissionUpdates(Mission $mission){
        $combineResult=[];
        $combineResult['mission_id']=$mission->id;
        $combineResult['user_id']=$mission->requests->first()->user_id;
        $combineResult['initial_cost']=$mission->initial_cost;
        $combineResult['extra_cost']=$mission->missionUpdates->sum('extra_cost');
        $combineResult['total']=$combineResult['extra_cost']+$combineResult['initial_cost'];
        return $combineResult;
    }
}



