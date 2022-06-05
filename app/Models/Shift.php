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
        'midday'
        ];
        Const Validation_Rules=[

            'name' => 'required|string',
            'start_time' => 'required|date_format:H:i:s',
            'end_time' => 'required|date_format:H:i:s',
            'midday' => 'required|date_format:H:i:s'
        ];
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
