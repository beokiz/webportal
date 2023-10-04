<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Seeder for "User" and related models tables
 *
 * @package Database\Seeders
 */
class UsersSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        \App\Models\Permission::factory()
            ->count(50)
            ->create();

        \App\Models\Role::factory()
            ->count(10)
            ->create();

        \App\Models\User::factory()
            ->count(50)
            ->create();
    }
}
