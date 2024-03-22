<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace Database\Factories;

use App\Models\Kita;
use App\Models\User;

/**
 * Evaluation Factory
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SurveyTimePeriod>
 * @package Database\Factories
 */
class SurveyTimePeriodFactory extends BaseFactory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() : array
    {
        return [
            'year'              => $this->faker->year,
            'age'               => $this->faker->randomElement(['2.5', '4.5']),
            'survey_start_date' => rand(0, 1) ? $this->faker->date() : null,
            'survey_end_date'   => rand(0, 1) ? $this->faker->date() : null,
        ];
    }
}
