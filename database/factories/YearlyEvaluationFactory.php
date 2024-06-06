<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace Database\Factories;

use App\Models\Kita;

/**
 * YearlyEvaluation Factory
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\YearlyEvaluation>
 * @package Database\Factories
 */
class YearlyEvaluationFactory extends BaseFactory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() : array
    {
        return [
            'year'                         => $this->faker->year,
            'kita_id'                      => Kita::inRandomOrder()->first()->id,
            'children_2_born_per_year'     => $this->faker->numberBetween(0, 999),
            'children_4_born_per_year'     => $this->faker->numberBetween(0, 999),
            'children_2_with_german_lang'  => $this->faker->numberBetween(0, 999),
            'children_4_with_german_lang'  => $this->faker->numberBetween(0, 999),
            'children_2_with_foreign_lang' => $this->faker->numberBetween(0, 999),
            'children_4_with_foreign_lang' => $this->faker->numberBetween(0, 999),
        ];
    }
}
