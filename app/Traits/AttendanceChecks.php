<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Models\Config;

trait AttendanceChecks {

    public function checkWeekend(){
        $days = Config::first()->weekend_days;
        $days = preg_split("/[\s,-]+/", $days);
        $day = date('D');
        foreach($days as $d){
            if(str_contains($d, $day)){
                return true;
            }
        }
        return false;
    }


}