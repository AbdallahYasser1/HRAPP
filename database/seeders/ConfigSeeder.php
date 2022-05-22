<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $configuration = [
            'company_name' => 'EvaPay', 'specificity' => 'Software', 'company_email' => 'eva@pay.com', 'company_phone' => '0121212', 'country' => 'egypt',
            'branches' => '1',
            'location' => 'alex',
            'longitude' => '29.955596923828125',
            'latitude' => '31.213575888245597',
            'distance' => '2000',
            'photo' => 'lat',
            'timezone' => 'Africa/Cairo',
            'fullDayAbsenceDeduction' => '1/30',
            'halfDayAbsenceDeduction' => '1/60',
            'fullDayAbsenceDeductionName' => 'Full Day Absence Deduction',
            'halfDayAbsenceDeductionName' => 'Half Day Absence Deduction',
        ];
        DB::table('configs')->insert($configuration);
    }
}
