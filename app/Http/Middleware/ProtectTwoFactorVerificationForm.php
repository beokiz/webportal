<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Protect Two-Factor Verification Form Middleware
 *
 * @package \App\Http\Middleware
 */
class ProtectTwoFactorVerificationForm
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('2fa*')) {
            // Redirect authenticated user to homepage
            if (auth()->check()) {
                return redirect(RouteServiceProvider::HOME);
            }

            // If 2FA verification code not exist in session - redirect to log-in form
            if (!$request->session()->has('2fa_data')) {
                return $this->unverified($request);
            }

            // If 2FA verification code expired - also redirect to log-in form
            if (now()->gt($request->session()->get('2fa_data.expires_at'))) {
                return $this->unverified($request, '2fa_expired');
            }
        }

        return $next($request);
    }

    /**
     * @param Request     $request
     * @param string|null $msgCode
     * @return RedirectResponse
     */
    protected function unverified(Request $request, ?string $msgCode = '') : RedirectResponse
    {
        $msgCode = !empty($msgCode) ? $msgCode : '2fa_error';

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('auth.login')
            ->withErrors(__("auth.{$msgCode}"));
    }
}
