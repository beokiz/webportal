<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * XSS Protection Middleware
 *
 * @package \App\Http\Middleware
 */
class XssProtection
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
        $inputs = $request->all();

        array_walk_recursive($inputs, function (&$input) {
            if ($input) {
                $input = strip_tags($input);
            }
        });

        $request->merge($inputs);

        return $next($request);
    }
}
