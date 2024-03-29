<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            ConfigSeeder::class,
            DepartmentSeeder::class,
            JobTitleSeeder::class,
            ShiftSeeder::class,
            RolesSeeder::class,
            UserSeeder::class,
            salarySeeder::class,
            VacationSeeder::class

        ]);
    }
}
