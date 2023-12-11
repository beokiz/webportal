<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace Database\Factories;

/**
 * Domain Factory
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kita>
 * @package Database\Factories
 */
class KitaFactory extends BaseFactory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() : array
    {
        return [
            'name'     => $this->faker->unique()->domainWord,
            'order'    => $this->faker->numberBetween(1, 999),
            'zip_code' => $this->faker->postcode,
        ];
    }
}
