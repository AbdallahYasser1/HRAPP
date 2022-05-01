<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $users = User::pluck('id');
        $user_id = $this->faker->randomElement($users);

        $start_time = $this->faker->dateTime('H:i:s');
        $leave_time   = $this->faker->dateTimeBetween($start_time, strtotime('+8 hours'));

        return [
            'user_id' => $this->faker->randomElement($users),
            'user_id' => $this->faker->unique()->randomElement($users),
            'date' => $this->faker->date(),
            'start_time' => $start_time,
            'leave_time' => $leave_time,
        ];
    }
}
