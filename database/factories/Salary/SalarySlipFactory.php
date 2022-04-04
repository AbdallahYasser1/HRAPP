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
        $terms = SalaryTerm::pluck('id');

        $user_id = $this->faker->randomElement($users);
        $term_id = User::find($user_id)->salaryTerm->id;

        return [
            'user_id' => $user_id,
            'salary_term_id' => $term_id,
//            'salary_adjustment_id' => SalaryAdjustment::all()->random()->id,
            'net_salary' => $this->faker->randomFloat(2, 1000, 100000),
            'period' => $this->faker->randomElement(['daily', 'monthly', 'yearly']),
        ];
    }
}
