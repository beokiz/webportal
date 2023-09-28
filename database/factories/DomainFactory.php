<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace Database\Factories;

use Illuminate\Support\Str;

/**
 * Domain Factory
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Domain>
 * @package Database\Factories
 */
class DomainFactory extends BaseFactory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() : array
    {
        $name = $this->faker->unique()->domainWord;

        return [
            'name'                       => $name,
            'abbreviation'               => Str::slug($name),
            'order'                      => $this->faker->numberBetween(1, 999),
            'age_2_red_threshold'        => $this->faker->numberBetween(1, 100),
            'age_2_red_threshold_daz'    => $this->faker->numberBetween(1, 100),
            'age_2_yellow_threshold'     => $this->faker->numberBetween(1, 100),
            'age_2_yellow_threshold_daz' => $this->faker->numberBetween(1, 100),
            'age_4_red_threshold'        => $this->faker->numberBetween(1, 100),
            'age_4_red_threshold_daz'    => $this->faker->numberBetween(1, 100),
            'age_4_yellow_threshold'     => $this->faker->numberBetween(1, 100),
            'age_4_yellow_threshold_daz' => $this->faker->numberBetween(1, 100),
        ];
    }
}
