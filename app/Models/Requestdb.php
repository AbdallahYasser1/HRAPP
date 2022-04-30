<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requestdb extends Model
{
    use HasFactory;
    protected $fillable=['id','user_id','start_date','end_date','is_approved','bywhom'];
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function bywhom()
    {
        return $this->belongsTo(User::class,'bywhom','id');
    }
    public function requestable()
    {
        return $this->morphTo();
    }
}
