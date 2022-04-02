<?php

namespace Database\Seeders;

use App\Models\Salary\SalaryAdjustment;
use App\Models\Salary\SalaryAdjustmentType;
use App\Models\Salary\SalarySlip;
use App\Models\Salary\SalaryTerm;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class salarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        SalaryTerm::truncate();
        SalarySlip::truncate();
        SalaryAdjustmentType::truncate();

        SalaryAdjustment::truncate();


        $salaryTermQuantity = 10;
        $salarySlipQuantity = 100;
        $salaryAdjustmentTypeQuantity = 20;
        $salaryAdjustmentQuantity = 10;
        $salaryHistoryQuantity = 10;

        SalaryTerm::factory($salaryTermQuantity)->create();
        SalaryAdjustmentType::factory($salaryAdjustmentTypeQuantity)->create();
        SalarySlip::factory($salarySlipQuantity)->create();
        SalaryAdjustment::factory($salaryAdjustmentQuantity)->create();




    }
}
