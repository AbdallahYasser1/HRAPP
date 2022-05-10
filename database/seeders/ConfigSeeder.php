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
            'company_name'=> 'EvaPay'
            , 'specifity'=> 'Software'
            , 'company_email'=> 'eva@pay.com'
            , 'company_phone'=> '0121212'
           ,'country'=> 'egypt' ,
            'branches' => '1',
 'location' => 'alex',
 'longtiude' => 'long',
 'latiude' => 'lat',
 'distance' => 'lat',
        ];
        DB::table('configs')->insert($configration);

    }
}
