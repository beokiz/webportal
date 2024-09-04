<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace Database\Factories;

/**
 * Setting Factory
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Setting>
 * @package Database\Factories
 */
class SettingFactory extends BaseFactory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() : array
    {
        return [
            'name'  => $this->faker->unique()->word,
            'value' => $this->faker->boolean,
        ];
    }
}
