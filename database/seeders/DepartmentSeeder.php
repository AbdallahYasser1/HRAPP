<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departments=[['name'=>"Department 1"],['name'=>"Department 2"],['name'=>"Department 3"],['name'=>"Department 4"]];
        Department::insert($departments);
    }
}
