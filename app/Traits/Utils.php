<?php

namespace App\Traits;


use App\Models\Requestdb;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

trait Utils
{



    function convertToDecimal($value)
    {
        if (is_numeric($value))
            return $value;

        if (!str_contains($value, "/"))
            return $value;

        $numbers = explode("/", $value);
        return round($numbers[0] / $numbers[1], 6);
    }


    public function takendays($type, $user_id)
    {
        $yearnow = Carbon::parse(date('Y-m-d', strtotime(Carbon::today())))->year;
        $takendays = Requestdb::with(['requestable'])
            ->join('users', 'requestdbs.user_id', 'users.id')
            ->whereIn('requestdbs.status', ["approved", "finished", "in-progress"])
            ->whereYear("requestdbs.start_date", $yearnow)
            ->join('vacations', 'requestdbs.requestable_id', 'vacations.id')
            ->where("user_id", '=', $user_id)
            ->where('requestdbs.requestable_type', '=', "App\\Models\\" . ucwords("vacation"))
            ->where('type', $type)
            ->sum('count');
        return $takendays;
    }
    public function VacationBalance($type, User $user)
    {
        if ($type == 'scheduled')
            $days = $user->vacationday->scheduled;
        else
            $days = $user->vacationday->unscheduled;

        return $days;
    }
public function CheckLeave(User $user){
    $requestes = Requestdb::with(['requestable'])
        ->join('users', 'requestdbs.user_id', 'users.id')
        ->where("requestdbs.user_id",'=',$user->id)
        ->where('requestdbs.status', '=', 'approved')
        ->where('requestdbs.requestable_type','=',"App\\Models\\".ucwords('leave'))
        ->where('requestdbs.start_date','=', date('Y-m-d', time()))->get();
return $requestes;

    }

}
