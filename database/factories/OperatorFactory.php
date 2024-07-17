<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace Database\Factories;

/**
 * Operator Factory
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Operator>
 * @package Database\Factories
 */
class OperatorFactory extends BaseFactory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() : array
    {
        return [
            'name'          => $this->faker->unique()->name,
            'self_training' => $this->faker->boolean,
        ];
    }
}
