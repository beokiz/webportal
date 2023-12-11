<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace Database\Factories;

/**
 * Permission Factory
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Permission>
 * @package Database\Factories
 */
class PermissionFactory extends BaseFactory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() : array
    {
        return [
            'name'       => $this->faker->unique()->domainWord,
            'guard_name' => config('auth.defaults.guard'),
        ];
    }
}
