<?php

namespace App\Models\Salary;

use App\Models\Config;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryAdjustmentType extends Model
{
    use HasFactory;

    protected $fillable = ['percent', 'name', 'amount'];

    public function getFullDayAbsenceAdjustment() {
        return $this->where('name', Config::first()->fullDayAbsenceDeductionName)->first();
    }

    public function getHalfDayAbsenceAdjustment() {
        return $this->where('name', Config::first()->halfDayAbsenceDeductionName)->first();
    }
}
