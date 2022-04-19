<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'description', 'initial_cost'];
    const Validation_Rules = ['start_date' => 'required|date', 'end_date' => 'required|date', 'description' => 'required|string', 'initial_cost' => 'required|numeric'];
    public function requests()
    {
        return $this->morphMany(Requestdb::class, 'requestable');
    }
    public function missionUpdates()
    {
        return $this->hasMany(MissionUpdate::class);
    }
}
