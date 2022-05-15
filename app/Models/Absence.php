<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class Absence extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'date'];
    
    protected function setKeysForSaveQuery($query)
    {
        $query
            ->where('user_id', '=', $this->getAttribute('user_id'))
            ->where('date', '=', $this->getAttribute('date'));

        return $query;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
