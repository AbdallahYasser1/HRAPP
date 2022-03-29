<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job_Title extends Model
{
    use HasFactory;
    protected $fillable=['job_name','department_id'];
    public function Department()
    {
        return $this->belongsTo(Job_Title::class);
    }
}

