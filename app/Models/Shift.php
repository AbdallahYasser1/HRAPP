<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        ];
        Const Validation_Rules=[
        
            'name' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date'
        ];
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
