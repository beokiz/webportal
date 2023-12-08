<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace Database\Factories;

use App\Models\Domain;

/**
 * Subdomain Factory
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subdomain>
 * @package Database\Factories
 */
class SubdomainFactory extends BaseFactory
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
            'domain_id' => Domain::inRandomOrder()->first()->id,
            'name'      => $name,
            'order'     => $this->faker->numberBetween(1, 999),
        ];
    }
}
