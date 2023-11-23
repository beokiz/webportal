<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Add User Timezone Header Middleware
 *
 * @package \App\Http\Middleware
 */
class AddUserTimezoneHeader
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request                                                                          $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!is_timezone($request->header('X-User-Timezone'))) {
            $request->headers->set('X-User-Timezone', date_default_timezone_get());
        }

        return $next($request);
    }
}
