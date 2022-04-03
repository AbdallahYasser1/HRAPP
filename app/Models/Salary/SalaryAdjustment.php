<?php

namespace App\Models\Salary;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryAdjustment extends Model
{
    use HasFactory;

    protected $fillable = ['salary_slip_id', 'salary_adjustment_type_id', 'amount', 'percent'];

    public function adjustmentType() {
        return $this->belongsTo(SalaryAdjustmentType::class);
    }

    public function slip() {
        return $this->belongsTo(SalarySlip::class);
    }
}
