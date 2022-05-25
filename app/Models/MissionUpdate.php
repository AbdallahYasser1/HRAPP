<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

use Illuminate\Validation\Rule;
class MissionUpdate extends Model
{
    use HasFactory;
    protected $fillable=['id','mission_id','description','date','extra_cost','approved_file'];
    const Validation_Rules = [  'description' => 'required|string', 'extra_cost' => 'required|numeric'];
    public function mission()
    {
        return $this->belongsTo(Mission::class);
    }

    protected function approvedFile(): Attribute
    {
        return Attribute::make(
            get: fn ($value) =>$value,
        );
    }

}
