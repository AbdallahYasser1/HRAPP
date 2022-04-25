<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable=['id','description'];
    public function requests()
    {
        return $this->morphMany(Requestdb::class, 'requestable');
    }
    public function employees()
    {
        return $this->hasMany(Task_employee::class);
    }
}
