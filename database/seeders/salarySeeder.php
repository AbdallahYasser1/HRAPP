<?php

namespace Database\Seeders;

use App\Models\Salary\SalaryAdjustment;
use App\Models\Salary\SalaryAdjustmentType;
use App\Models\Salary\SalarySlip;
use App\Models\Salary\SalaryTerm;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Config;
use App\Traits\Utils;

class salarySeeder extends Seeder
{
    use Utils;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::statement('SET FOREIGN_KEY_CHECKS = 0');


        // SalaryAdjustment::truncate();
        // SalarySlip::truncate();
        // SalaryAdjustmentType::truncate();
        // SalaryTerm::truncate();


        $salaryTermQuantity = 27;
        // $salarySlipQuantity = 150;
        $salaryAdjustmentTypeQuantity = 20;
        // $salaryAdjustmentQuantity = 150;

        SalaryTerm::factory($salaryTermQuantity)->create();
        SalaryAdjustmentType::factory($salaryAdjustmentTypeQuantity)->create();
        // SalarySlip::factory($salarySlipQuantity)->create();
        // SalaryAdjustment::factory($salaryAdjustmentQuantity)->create();
        SalaryAdjustmentType::factory(1)->create([
            'id' => 1,
            'name' => Config::first()->fullDayAbsenceDeductionName,
            'percent' => $this->convertToDecimal(Config::first()->fullDayAbsenceDeduction),
            'amount' => 0,
            'isAll' => false,
        ]);
        SalaryAdjustmentType::factory(1)->create([
            'id' => 2,
            'name' => Config::first()->halfDayAbsenceDeductionName,
            'percent' => $this->convertToDecimal(Config::first()->halfDayAbsenceDeduction),
            'amount' => 0,
            'isAll' => false,
        ]);


    }
}
