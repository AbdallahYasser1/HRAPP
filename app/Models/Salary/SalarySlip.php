<?php

namespace App\Models\Salary;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalarySlip extends Model
{
    use HasFactory;

    protected $fillable = ['net_salary', 'period', 'salary_term_id', 'user_id', ];


    public function employee() {
        return $this->belongsTo(User::class);
    }

    public function term() {
        return $this->belongsTo(salaryTerm::class);
    }

    public function adjustments() {
        return $this->hasMany(SalaryAdjustment::class, 'salary_slip_id');
    }
}
