<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Profile extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'department_id', 'job__title_id', 'image', 'image_approved'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function job_title()
    {
        return $this->belongsTo(Job_Title::class,'job__title_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn ($value) =>$value==''?null :$value,
        );
    }
}
