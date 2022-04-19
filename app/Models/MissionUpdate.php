<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MissionUpdate extends Model
{
    use HasFactory;
    protected $fillable=['id','mission_id','date','extra_cost','approved_file'];
    public function mission()
    {
        return $this->belongsTo(Mission::class);
    }
}
