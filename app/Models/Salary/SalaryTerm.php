<?php

namespace App\Models\Salary;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryTerm extends Model
{
    use HasFactory;

    protected $fillable = [
        'salary_agreed',
        'end'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function slips() {
        return $this->hasMany(SalarySlip::class);
    }
}
