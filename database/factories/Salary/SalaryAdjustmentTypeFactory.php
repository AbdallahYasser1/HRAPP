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
            'id' => $this->faker->numberBetween(3, ),
            'name' => $this->faker->name(),
            'percent' => $this->faker->randomFloat(2, -0.03, 0.10),
            'amount' => $this->faker->numberBetween(-3000, 3000),
            'isAll' => $this->faker->boolean(),

        ];
    }
}
