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
            'email_verified_at' => $createdAt,
            'created_at'        => $createdAt,
            'updated_at'        => $createdAt,
        ];

        /*
         * Create "Super-Admin" User
         */
        $superAdminUser = \App\Models\User::create(array_merge($commonOptions, [
            'first_name'              => 'Admin',
            'last_name'               => 'Super',
            'email'                   => 'beokiznimda@gmail.com',
            'password'                => '$2y$10$U3Da2rmDrEtZ/rJyg8SkJuj9XYAj7xzyfbAeMbWGrACm9hAx6/zz6',
            'two_factor_auth_enabled' => false,
        ]));

        // Assign user to 'Super-Admin' Role
        $superAdminRole = \App\Models\Role::query()
            ->where('name', config('permission.project_roles.super_admin'))
            ->first();

        if ($superAdminRole) {
            $superAdminUser->syncRoles([$superAdminRole->id]);
        }
    }
};
