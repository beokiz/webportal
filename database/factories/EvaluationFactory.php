<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace Database\Factories;

use App\Models\Kita;
use App\Models\Subdomain;
use App\Models\User;
use Illuminate\Support\Str;

/**
 * Evaluation Factory
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Evaluation>
 * @package Database\Factories
 */
class EvaluationFactory extends BaseFactory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() : array
    {
        return [
            'uuid'        => $this->faker->uuid,
            'user_id'     => User::inRandomOrder()->first()->id,
            'kita_id'     => Kita::inRandomOrder()->first()->id,
            'age'         => $this->faker->randomElement(['2.5', '4.5']),
            'is_daz'      => $this->faker->boolean,
            'data'        => [],
            'finished_at' => rand(0, 1) ? $this->faker->dateTimeBetween('-1 month', 'now') : null,
        ];
    }
}
