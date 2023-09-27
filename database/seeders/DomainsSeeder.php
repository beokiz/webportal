<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Seeder for "Domain" and related models tables
 *
 * @package Database\Seeders
 */
class DomainsSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        \App\Models\Domain::factory()
            ->count(50)
            ->hasSubdomain(rand(1, 5))
            ->create();
    }
}
