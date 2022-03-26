<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wfh extends Model
{
    use HasFactory;
    protected $fillable=['id'];
    public function requests()
    {
        return $this->morphOne(Requestdb::class, 'requestable');
    }
}
