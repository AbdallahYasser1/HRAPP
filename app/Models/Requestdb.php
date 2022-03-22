<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requestdb extends Model
{
    use HasFactory;
    protected $fillable=['id','user_id','start_date','end_date','is_approved'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function requestable()
    {
        return $this->morphTo();
    }
}
