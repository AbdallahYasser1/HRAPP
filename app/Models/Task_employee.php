<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task_employee extends Model
{
    use HasFactory;
    protected $fillable=['id','task_id','employee_id','status'];
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
