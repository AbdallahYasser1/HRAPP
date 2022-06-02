<?php

namespace Database\Factories;

use App\Models\Shift;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Nette\Utils\Random;
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
    function genrateID() {
        $ID = mt_rand(100000, 999999999); // better than rand()

        if ($this->IDExists($ID)) {
            return $this->genrateID();
        }

        return $ID;
    }

    function IDExists($ID) {
        return User::where('id',$ID)->exists();
    }
    public function definition()
    {

        return [
            'id'=>$this->genrateID(),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone'=>$this->faker->unique()->phoneNumber(),
            'birthdate'=>date("Y-m-d", rand( strtotime("Jan 01 1977"), strtotime("Nov 01 2000") ) ),
            'active'=>true,
            'first_time_login'=>false,
            'status'=>'available',
            'password' => '123456789',
            'shift_id'=>Shift::all()->Random(),// password
            'remember_token' => Str::random(10),
            'supervisor'=>null
        ];
    }

    //public function configure()
   // {
    //    return $this->afterMaking(function (User $user) {

    //        $user->assignRole(Role::all()->random());

       // });
   // }
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
