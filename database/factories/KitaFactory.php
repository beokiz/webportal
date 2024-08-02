<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace Database\Factories;

use App\Models\Kita;

/**
 * Kita Factory
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
            'type'                  => $this->faker->randomElement(Kita::TYPES),
            'approved'              => $this->faker->boolean,
            'name'                  => $this->faker->unique()->domainWord,
            'provider_of_the_kita'  => $this->faker->company,
            'city'                  => $this->faker->city,
            'district'              => $this->faker->randomElement(['Zentral', 'Nord', 'Süd', 'Ost', 'West', 'Innenstadt', 'Oberstadt', 'Stadtmitte']),
            'number'                => $this->faker->randomNumber(),
            'street'                => $this->faker->streetName,
            'house_number'          => $this->faker->randomNumber(),
            'num_pedagogical_staff' => rand(1, 10),
            'notes'                 => rand(0, 1) ? $this->faker->sentence : null,
            'additional_info'       => rand(0, 1) ? $this->faker->sentence : null,
            'order'                 => $this->faker->numberBetween(1, 999),
            'zip_code'              => $this->faker->postcode,
        ];
    }
}
