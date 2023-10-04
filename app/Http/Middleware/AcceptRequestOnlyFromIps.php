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
 * Accept Request Only From IPs Middleware
 *
 * @package \App\Http\Middleware
 */
class AcceptRequestOnlyFromIps
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param array                    $ips
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$ips)
    {
        foreach ($request->getClientIps() as $ip) {
            if (!is_valid_ip($ip, $ips) && !is_valid_ip_range($ip, $ips)) {
                throw new NotFoundHttpException();
            }
        }

        return $next($request);
    }
}
