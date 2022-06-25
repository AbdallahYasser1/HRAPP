<?php

namespace Database\Factories\Salary;

use App\Models\Salary\SalaryAdjustment;
use App\Models\Salary\SalarySlip;
use App\Models\Salary\SalaryTerm;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Salary\SalarySlip>
 */
class SalarySlipFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = SalarySlip::class;
    public function definition()
    {
        $users = User::pluck('id');

        $user_id = $this->faker->randomElement($users);
        $user = User::find($user_id);
        $term_id = User::find($user_id)->salaryTerm;

        return [
            'user_id' => $user_id,
            'user_name' => $user->name,
            'salary_term_id' => $term_id,
            // 'net_salary' => $this->faker->randomFloat(2, 1000, 100000),
            'date' => $this->faker->date(),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
        ];
    }
}
