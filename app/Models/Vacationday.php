<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacationday extends Model
{
    use HasFactory;
    const Validation_Create_Vacation_Day=["user_id"=>"required|int","scheduled"=>"required|int","unscheduled"=>"required|int"];
    const Validation_Update_Vacation_Day=["scheduled"=>"required|int","unscheduled"=>"required|int"];
    protected $table="vacation_day";
    protected $primaryKey ="user_id";
    protected $fillable=[
        'user_id',
        'scheduled',
        'unscheduled'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
