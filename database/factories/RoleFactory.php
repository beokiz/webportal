<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace Database\Factories;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Str;

/**
 * Role Factory
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 * @package Database\Factories
 */
class RoleFactory extends BaseFactory
{
    /**
     * Configure the factory.
     *
     * @return $this
     */
    public function configure() : RoleFactory
    {
        return $this->afterCreating(function (Role $role) {
            $permissions = Permission::inRandomOrder()
                ->take(rand(5, 15))
                ->pluck('id')
                ->toArray();

            $role->syncPermissions($permissions);
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() : array
    {
        $name = $this->faker->unique()->word;

        return [
            'name'                => $name,
            'human_name'          => Str::slug($name),
            'guard_name'          => config('auth.defaults.guard'),
            'is_institution_role' => $this->faker->boolean,
        ];
    }
}
