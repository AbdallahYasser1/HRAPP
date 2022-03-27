<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacation extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'type'];

    public function requests()
    {
        return $this->morphMany(Requestdb::class, 'requestable');
    }

}
