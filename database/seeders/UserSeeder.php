<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Job_Title;
use Database\Factories\UserFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Profile;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //User::factory(10)->create();
        User::factory(1)->create()
            ->each(function ($user) {
                $user->assignRole('Admin');
            });
        User::factory(1)->create()
            ->each(function ($user) {
                $user->assignRole('HR');
            });
        User::factory(3)->create()
            ->each(function ($user) {
                $user->assignRole('Accountant');
            });
        $department = Department::all()->random();
        $normalUser = User::factory(1)->has(Profile::factory()->state(['department_id' => $department->id,'job__titles_id'=>Job_Title::where('department_id','=',$department->id)->first()->id]))->create()
            ->each(function ($user) {
                $user->assignRole('Normal');
            });
        User::factory(10)->has(Profile::factory()->state(['department_id' => $department->id,'job__titles_id'=>Job_Title::where('department_id','=',$department->id)->first()->id]))->state(['supervisor' => $normalUser[0]->id])->create()
            ->each(function ($user) {
                $user->assignRole('Normal');
            });
        $department = Department::all()->random();
        $normalUser2 = User::factory(1)->has(Profile::factory()->state(['department_id' => $department->id,'job__titles_id'=>Job_Title::where('department_id','=',$department->id)->first()->id]))->create()
            ->each(function ($user) {
                $user->assignRole('Normal');
            });
        User::factory(10)->has(Profile::factory()->state(['department_id' => $department->id,'job__titles_id'=>Job_Title::where('department_id','=',$department->id)->first()->id]))->state(['supervisor' => $normalUser2[0]->id])->create()
            ->each(function ($user) {
                $user->assignRole('Normal');
            });
    }
}
