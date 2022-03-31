<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    protected $fillable=['name'];

    public function job_titles()
    {
        return $this->hasMany(Job_Title::class);
    }
    public function profile(){
        return $this->hasMany(Profile::class);
    }
}
