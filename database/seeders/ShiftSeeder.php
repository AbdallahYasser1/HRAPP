<?php

namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Shift::create(['name'=>'Morning Shift','start_time'=>'09:00','midday'=>'13:00','end_time'=>'17:00']);
        Shift::create(['name'=>'Night Shift','start_time'=>'10:00','midday'=>'14:00','end_time'=>'18:00']);
    }
}
