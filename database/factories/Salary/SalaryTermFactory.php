<?php

namespace Database\Factories\Salary;

use App\Models\Salary\SalaryTerm;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Salary\SalaryTerm>
 */
class SalaryTermFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = SalaryTerm::class;
    public function definition()
    {
        $users = User::pluck('id');
        return [
            'user_id' => $this->faker->unique()->randomElement($users),
            'start' => $this->faker->dateTime(),
            'end' => $this->faker->dateTimeBetween('now', '+30 years'),
            'salary_agreed' => $this->faker->numberBetween(5000, 50000),
        ];
    }
}
