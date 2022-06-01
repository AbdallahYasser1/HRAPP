<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Job_Title;
use Database\Factories\UserFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Profile;
use App\Models\Vacationday;

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
        User::factory(1)->has(Vacationday::factory()->state([]))->has(Profile::factory()->state(['department_id' => 1,'job__title_id'=>1, "image"=>"https://res.cloudinary.com/dokaaek9w/image/upload/v1653746912/profile_images/IMG-20220403-WA0021_yvig6b.jpg"]))->create()
            ->each(function ($user) {
                $user->assignRole('Admin');
            });

        User::factory(1)->has(Vacationday::factory()->state([]))->has(Profile::factory()->state(['department_id' => 2,'job__title_id'=>2, "image"=>"https://res.cloudinary.com/dokaaek9w/image/upload/v1653746912/profile_images/IMG-20220403-WA0021_yvig6b.jpg"]))->create()
            ->each(function ($user) {
                $user->assignRole('HR');
            });
        User::factory(3)->has(Vacationday::factory()->state([]))->has(Profile::factory()->state(['department_id' => 3,'job__title_id'=>3, "image"=>"https://res.cloudinary.com/dokaaek9w/image/upload/v1653746912/profile_images/IMG-20220403-WA0021_yvig6b.jpg"]))->create()
            ->each(function ($user) {
                $user->assignRole('Accountant');
            });
        $department = Department::all()->random();
        $normalUser = User::factory(1)->has(Vacationday::factory()->state([]))->has(Profile::factory()->state(['department_id' => $department->id,'job__title_id'=>$department->id]))->create()
            ->each(function ($user) {
                $user->assignRole('Normal');
            });
        User::factory(10)->has(Vacationday::factory()->state([]))->has(Profile::factory()->state(['department_id' => $department->id,'job__title_id'=>$department->id , "image"=>"https://res.cloudinary.com/dokaaek9w/image/upload/v1653746912/profile_images/IMG-20220403-WA0021_yvig6b.jpg"] ))->state(['supervisor' => $normalUser[0]->id])->create()
            ->each(function ($user) {
                $user->assignRole('Normal');
            });
        $department = Department::all()->random();
        $normalUser2 = User::factory(1)->has(Vacationday::factory()->state([]))->has(Profile::factory()->state(['department_id' => $department->id,'job__title_id'=>$department->id , "image"=>"https://res.cloudinary.com/dokaaek9w/image/upload/v1653746912/profile_images/IMG-20220403-WA0021_yvig6b.jpg"]))->create()
            ->each(function ($user) {
                $user->assignRole('Normal');
            });
        User::factory(10)->has(Vacationday::factory()->state([]))->has(Profile::factory()->state(['department_id' => $department->id,'job__title_id'=>$department->id , "image"=>"https://res.cloudinary.com/dokaaek9w/image/upload/v1653746912/profile_images/IMG-20220403-WA0021_yvig6b.jpg"] ))->state(['supervisor' => $normalUser2[0]->id])->create()
            ->each(function ($user) {
                $user->assignRole('Normal');
            });
    }
}
