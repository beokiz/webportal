<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace Database\Factories;

use App\Models\Subdomain;
use Illuminate\Support\Str;

/**
 * Milestone Factory
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Milestone>
 * @package Database\Factories
 */
class MilestoneFactory extends BaseFactory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() : array
    {
        return [
            'subdomain_id' => Subdomain::inRandomOrder()->first()->id,
            'abbreviation' => Str::slug($this->faker->unique()->domainWord),
            'title'        => $this->faker->words(rand(3, 7), true),
            'text'         => $this->faker->text(),
            'order'        => $this->faker->numberBetween(1, 999),
            'emphasis'     => $this->faker->numberBetween(1, 100),
            'emphasis_daz' => $this->faker->numberBetween(1, 100),
            'age'          => $this->faker->randomElement(['2.5', '4.5']),
        ];
    }
}
