<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use App\Models\User;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id'=>rand(100000,80000)+rand(1,1000),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone'=>$this->faker->unique()->phoneNumber(),
            'birthdate'=>date("Y-m-d", rand( strtotime("Jan 01 1977"), strtotime("Nov 01 2000") ) ),
            'active'=>true,
            'first_time_login'=>false,
            'status'=>'active',
            'password' => Hash::make('123456789'), // password
            'remember_token' => Str::random(10),
        ];
    }
    public function configure()
    {
        return $this->afterMaking(function (User $user) {
           
            $user->assignRole(Role::all()->random());

        });
    }
    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
