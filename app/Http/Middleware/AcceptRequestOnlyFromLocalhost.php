<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Accept Request Only From Localhost Middleware
 *
 * @package \App\Http\Middleware
 */
class AcceptRequestOnlyFromLocalhost
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!is_private_ip($request->ip())) {
            throw new NotFoundHttpException();
        }

        return $next($request);
    }
}
