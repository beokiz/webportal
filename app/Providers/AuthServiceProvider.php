<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Providers;

use App\Models\User;
use App\Policies\UserRolePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

/**
 * Auth Service Provider
 *
 * @package \App\Providers
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserRolePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $this->defineSuperAdmin();
    }

    /**
     * Define Spatie Permissions 'super-admin' role
     *
     * @return void
     */
    private function defineSuperAdmin()
    {
//        Gate::before(function ($user, $ability) {
//            return $user->hasRole(config('permission.project_roles.super_admin')) ? true : null;
//        });
    }
}
