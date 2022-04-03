<?php

namespace Database\Factories\Salary;

use App\Models\Salary\SalaryAdjustmentType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class SalaryAdjustmentTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = SalaryAdjustmentType::class;
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'percent' => $this->faker->randomFloat(2, 0, 99),
            'is_working_hours' => $this->faker->boolean(),
            'is_other' => $this->faker->boolean(),
        ];
    }
}
