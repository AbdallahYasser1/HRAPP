<?php

namespace App\Models\Salary;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryAdjustmentType extends Model
{
    use HasFactory;

    protected $fillable = ['percent', 'name', 'is_working_hours', 'is_other'];
}
