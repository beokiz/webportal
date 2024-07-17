<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;

/**
 * User Factory
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 * @package Database\Factories
 */
class UserFactory extends BaseFactory
{
    /**
     * Configure the factory.
     *
     * @return $this
     */
    public function configure() : UserFactory
    {
        return $this->afterCreating(function (User $user) {
            $roleId = Role::inRandomOrder()->first()->id;

            $user->assignRole($roleId);
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name'              => $this->faker->firstName(),
            'last_name'               => $this->faker->lastName(),
            'email'                   => $this->faker->unique()->safeEmail(),
            'email_verified_at'       => $this->faker->dateTimeBetween('-1 month', 'now'),
            'password'                => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token'          => Str::random(10),
            'two_factor_auth_enabled' => $this->faker->boolean,
            'last_seen_at'            => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
