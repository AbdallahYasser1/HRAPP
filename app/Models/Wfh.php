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
        return $this->morphMany(Requestdb::class, 'requestable');
    }

}
