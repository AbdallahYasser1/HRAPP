<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job_Title extends Model
{
    use HasFactory;
    protected $fillable=['job_name'];

    public function Profile()
    {
        return $this->belongsTo(Profile::class);
    }
}

