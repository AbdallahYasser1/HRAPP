<?php

namespace Database\Factories\Salary;

use App\Models\Salary\SalaryAdjustment;
use App\Models\Salary\SalaryAdjustmentType;
use App\Models\Salary\SalarySlip;
use App\Models\Salary\SalaryTerm;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class SalaryAdjustmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = SalaryAdjustment::class;
    public function definition()
    {
        $slips = SalarySlip::pluck('id');
        return [
            'salary_slip_id' => SalarySlip::all()->isEmpty()? null : $this->faker->randomElement($slips),
            'salary_adjustment_type_id' => SalaryAdjustmentType::all()->random()->id,
            'amount' => $this->faker->numberBetween(1000, 100000),
            'percent' => $this->faker->randomFloat(2, 0, 99),
            'date' => $this->faker->dateTimeBetween('-1 year', 'now'),

        ];
    }
}
