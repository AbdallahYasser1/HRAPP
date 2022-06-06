<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $fillable=[
        'date','user_id','start_time','leave_time'
    ];
    // protected $primaryKey = null;

    // public $incrementing = false;

    protected function setKeysForSaveQuery($query)
    {
        $query
            ->where('user_id', '=', $this->getAttribute('user_id'))
            ->where('date', '=', $this->getAttribute('date'));

        return $query;
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
