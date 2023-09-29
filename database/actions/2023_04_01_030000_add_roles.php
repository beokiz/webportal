<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

declare(strict_types = 1);

use DragonCode\LaravelActions\Action;

return new class () extends Action {
    /**
     * Run the actions.
     *
     * @return void
     */
    public function __invoke() : void
    {
        $createdAt = now();

        $commonOptions = [
            'guard_name' => config('auth.defaults.guard'),
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];

        \App\Models\Role::insert([
            array_merge($commonOptions, [
                'name'                => config('permission.project_roles.super_admin'),
                'human_name'          => "Superadmin",
                'is_institution_role' => false,
            ]),
            array_merge($commonOptions, [
                'name'                => config('permission.project_roles.admin'),
                'human_name'          => "Admin",
                'is_institution_role' => false,
            ]),
            array_merge($commonOptions, [
                'name'                => config('permission.project_roles.monitor'),
                'human_name'          => "Monitor",
                'is_institution_role' => false,
            ]),
            array_merge($commonOptions, [
                'name'                => config('permission.project_roles.monitor_oe'),
                'human_name'          => "Monitor oE",
                'is_institution_role' => false,
            ]),
            array_merge($commonOptions, [
                'name'                => config('permission.project_roles.manager'),
                'human_name'          => "Manager",
                'is_institution_role' => true,
            ]),
            array_merge($commonOptions, [
                'name'                => config('permission.project_roles.employer'),
                'human_name'          => "Mitarbeiter",
                'is_institution_role' => true,
            ]),
        ]);
    }
};
