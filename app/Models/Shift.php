<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        ];
        Const Validation_Rules=[
        
            'name' => 'required|string',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i'
        ];
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
