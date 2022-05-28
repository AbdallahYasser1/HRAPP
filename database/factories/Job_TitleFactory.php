<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Job_Title;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job_Title>
 */
class Job_TitleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "job_name"=>$this->faker->jobTitle(),

        ];
    }
}
