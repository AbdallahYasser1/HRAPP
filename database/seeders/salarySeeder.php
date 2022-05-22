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

        // SalaryAdjustmentType::factory(1)->create([
        //     'name' => 'Half Day Absence Deduction',
        //     'percent' => 1.665,
        //     'amount' => 0,
        // ]);
        // SalaryAdjustmentType::factory(1)->create([
        //     'name' => 'Full Day Absence Deduction',
        //     'percent' => 3.333,
        //     'amount' => 0,
        // ]);

    }
}
