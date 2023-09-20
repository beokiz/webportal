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

        $roles = [];

        foreach (config('permission.project_roles') as $role) {
            $roles[] = array_merge($commonOptions, [
                'name' => $role,
            ]);
        }

        \App\Models\Role::insert($roles);
    }
};
