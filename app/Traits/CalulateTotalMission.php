<?php
namespace App\Traits;
use Illuminate\Support\Facades\DB;
/**
 * SELECT (m.initial_cost+sum(mu.extra_cost)) as total
 * FROM `missions` m
 * INNER JOIN
 * `mission_updates` mu
 * on m.id=mu.mission_id 
 * WHERE m.id= 1
 */
//+sum(mission_updates.extra_cost)) as mission_upates_total
trait CalulateTotalMission
{
    public function calculateTotalMissionAndMissionUpdates($mission_id){
       $mission_id=1;
        $intialCost=DB::table('missions')->select('missions.id','missions.initial_cost')->where("missions.id",'=',$mission_id)->get();
        $extra_cost=DB::table('missions')->join('mission_updates','missions.id','=','mission_updates.mission_id')->groupBy('missions.id')->select('missions.id',DB::raw('sum(mission_updates.extra_cost) as extraCostTotal'))->where("missions.id",'=',$mission_id)->get();
        $combineResult=[];
        $combineResult['id']=$intialCost[0]->id;
        $combineResult['initial_cost']=$intialCost[0]->initial_cost;
        if( $extra_cost->count()){
            $combineResult['extra_cost']=$extra_cost[0]->extraCostTotal;
            $combineResult['total']=$intialCost[0]->initial_cost+$extra_cost[0]->extraCostTotal;
            
        }else{
            $combineResult['total']=$intialCost[0]->initial_cost;
            
        }
         return $combineResult;
    }
}



