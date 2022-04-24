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
         $configration = [
            ['key' => 'company_name', 'value' => 'EvaPay'],
            ['key' => 'specifity', 'value' => 'Software'],
            ['key' => 'company_email', 'value' => 'eva@pay.com'],
            ['key' => 'company_phone', 'value' => '0121212'],
            ['key' => 'country', 'value' => 'egypt'],
            ['key' => 'branches', 'value' => '1'],
            ['key' => 'photo', 'value' => 'location'],
        ];
        DB::table('configs')->insert($configration);

    }
}
