<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Database Seeder
 *
 * @package Database\Seeders
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
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

        \App\Models\Domain::factory()
            ->count(50)
            ->hasSubdomains(rand(1, 5))
            ->create();

        \App\Models\Milestone::factory()
            ->count(125)
            ->create();

        \App\Models\Kita::factory()
            ->count(10)
            ->create();

        \App\Models\SurveyTimePeriod::factory()
            ->count(10)
            ->create();

        \App\Models\YearlyEvaluation::factory()
            ->count(10)
            ->create();

        \App\Models\Setting::factory()
            ->count(30)
            ->create();

        \App\Models\Operator::factory()
            ->count(10)
            ->create();
    }
}
