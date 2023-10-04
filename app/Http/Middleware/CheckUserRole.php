<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

/**
 * Check User Role Middleware
 *
 * @package \App\Http\Middleware
 */
class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param array                    $roles
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        if (Auth::check() && $request->user()->hasRole(config('permission.project_roles.super_admin'))) {
            return $next($request);
        }

        if (!Auth::check() || !$request->user()->hasRole($roles)) {
            return redirect()->route('dashboard.index')
                ->withErrors(__('user_does_not_have_the_required_roles_exception_msg'));
        }

        return $next($request);
    }
}
