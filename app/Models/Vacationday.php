<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacationday extends Model
{
    use HasFactory;
    protected $table="vacation_day";
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
